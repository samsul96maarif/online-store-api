<?php
/*
 * Copyright (c) 7/13/21, 7:55 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Models\ComActivityTable;
use App\Models\ComLog;
use App\Models\ComLogDetail;
use App\Models\Product;
use App\Repositories\Contracts\ProductContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRepository extends Repository implements ProductContract
{
    protected $name = 'Product', $using_log_modul = true, $order_by = 'desc';
    public function getModel()
    {
        return new Product();
    }

    public function removeWithLog($id, Request $request)
    {
        try {
            $result = $this->getModel()->findOrFail($id);
            $oldValue = $result->toArray();
            DB::beginTransaction();
            $com_activity_name = is_null($this->delete_activity_name) ? 'Delete '.$this->name : $this->create_activity_name;
            $comLogId = ComLog::ins()->pop($com_activity_name, $request);
            $comATModel = ComActivityTable::findByTableAndActivity($this->getModel()->getTable(), $com_activity_name);
            if (is_null($comATModel)) throw new \Exception("Something wrong");
            ComLogDetail::ins()->pop($comLogId, 'delete', $comATModel->id, [], $oldValue, $id);
            if (!$result->delete()) throw new \Exception("Data deleted unsuccessfully");
            DB::commit();
            return true;
        } catch (\Exception $e) {
            $this->makeLogInfo($e->getMessage());
            DB::rollBack();
            throw $e;
        }
    }
}
