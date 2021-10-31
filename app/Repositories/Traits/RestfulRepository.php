<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021-2021.
 */

namespace App\Repositories\Traits;

use App\Models\ComActivityTable;
use App\Models\ComLog;
use App\Models\ComLogDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Default restful function
 * Trait RestfulRepository
 * @package App\Repositories\Traits
 */
trait RestfulRepository
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function fetch(Request $request)
    {
        try {
            $data = $this->getModel()->orderBy($this->sortBy, $this->order_by)->paginate($request->per_page);
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public function list()
    {
        try{
            return $this->getModel()->orderBy($this->sortBy, $this->order_by)->get();
        }catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function byId($id)
    {
        try {
            return $this->getModel()->find($id);
        }catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $model = $this->getModel()->pop($request);
            if(!$model->save()) throw new \Exception('Data stored unsuccessfully');
            if ($this->using_log_modul){
                $com_activity_name = is_null($this->create_activity_name) ? 'Create '.$this->name : $this->create_activity_name;
                $comLogId = ComLog::ins()->pop($com_activity_name, $request);
                $comATModel = ComActivityTable::findByTableAndActivity($this->getModel()->getTable(), $com_activity_name);
                if (is_null($comATModel)) throw new \Exception("Something wrong");
                ComLogDetail::ins()->pop($comLogId, 'create', $comATModel->id, $model->toArray(), [], $model->id);
            }
            DB::commit();
            return  $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $result = $this->getModel()->find($id);
            $oldValue = $result->toArray();
            if (!$result->pop($request)->save()) throw new \Exception('Data updated unsuccessfully');
            if ($this->using_log_modul){
                $com_activity_name = is_null($this->update_activity_name) ? 'Update '.$this->name : $this->create_activity_name;
                $comLogId = ComLog::ins()->pop($com_activity_name, $request);
                $comATModel = ComActivityTable::findByTableAndActivity($this->getModel()->getTable(), $com_activity_name);
                if (is_null($comATModel)) throw new \Exception("Something wrong");
                ComLogDetail::ins()->pop($comLogId, 'update', $comATModel->id, $result->toArray(), $oldValue, $id);
            }
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function remove($id)
    {
        try {
            $result = $this->getModel()->find($id);
            if (!$result->delete()) throw new \Exception("Data deleted unsuccessfully");
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
