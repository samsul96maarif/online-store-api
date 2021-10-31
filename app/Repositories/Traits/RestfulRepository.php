<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021-2021.
 */

namespace App\Repositories\Traits;

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
            $data = $this->getModel()->paginate($request->per_page);
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
            return $this->getModel()->get();
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
            $model = $this->getModel()->pop($request);
            if(!$model->save()) throw new \Exception('Data stored unsuccessfully');
            return  $model;
        } catch (\Exception $e) {
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
            $result = $this->getModel()->find($id);
            if (!$result->pop($request)->save()) throw new \Exception('Data updated unsuccessfully');
            return $result;
        } catch (\Exception $e) {
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
