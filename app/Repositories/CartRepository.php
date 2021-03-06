<?php
/*
 * Copyright (c) 7/13/21, 7:55 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Constant\ComLogDetailConstant;
use App\Constant\RoleConstant;
use App\Models\Cart;
use App\Models\ComActivityTable;
use App\Models\ComLog;
use App\Models\ComLogDetail;
use App\Models\Product;
use App\Repositories\Contracts\CartContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartRepository extends Repository implements CartContract
{
    protected $name = 'Cart';
    public function getModel()
    {
        return Cart::ins();
    }

    public function list()
    {
        try {
            $data = $this->getModel()->with('product');
            if (!auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])) $data = $data->where('user_id', auth()->user()->id);
            return $data->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function byId($id)
    {
        try {
            $where = ['id' => $id];
            if (!auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])) $where['user_id'] = auth()->user()->id;
            $data = $this->getModel()->where($where)->with('product');
            return $data->firstOrFail();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store($request)
    {
        try {
            $modelIdArr = [];
            $oldValueArr = [];
            $newValueArr = [];
            $actionArr = [];
            $tableArr= [];

            if(is_null($request->product_id)) throw new \Exception("Pilih produk terlebih dahulu!");
            $product = Product::findOrFail($request->product_id);

            $oldValueArr[] = $product->toArray();

            if (auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])) {
                $userId = $request->user_id ?? auth()->user()->id;
            } else {
                $userId = auth()->user()->id;
            }
            $request->request->add(['user_id' => $userId]);

            $model = Cart::where([
                'product_id' => $request->product_id,
                'user_id' => $userId
            ])->first();

            DB::beginTransaction();
            if ($product->stock - $request->qty < 0) throw new \Exception('Stok barang tidak cukup');
            $product->stock -= $request->qty;
            if (!$product->save()) throw new \Exception('Gagal update stok barang');

            $newValueArr[] = $product->toArray();
            $modelIdArr[] = $product->id;
            $actionArr[] = ComLogDetailConstant::UPDATE_ACTION;
            $tableArr[] = 'products';

            if (is_null($model)){
                $model = $this->getModel()->pop($request);
            } else {
                $model->qty += $request->qty;
            }
            if (!$model->save()) throw new \Exception("Gagal menambah barang ke dalam keranjang");

            $modelIdArr[] = $model->id;
            $oldValueArr[] = [];
            $newValueArr[] = $model->toArray();
            $actionArr[] = ComLogDetailConstant::CREATE_ACTION;
            $tableArr[] = 'carts';

            $com_activity_name = is_null($this->create_activity_name) ? 'Create '.$this->name : $this->create_activity_name;
            $comLogId = ComLog::ins()->pop($com_activity_name, $request);
            for ($i=0; $i<count($modelIdArr); $i++){
                $comATModel = ComActivityTable::findByTableAndActivity($tableArr[$i], $com_activity_name);
                if (is_null($comATModel)) throw new \Exception("Something wrong");
                ComLogDetail::ins()->pop($comLogId, $actionArr[$i], $comATModel->id, $newValueArr[$i], $oldValueArr[$i], $modelIdArr[$i], $i+1);
            }
            DB::commit();
            return  $model;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        try {
            $where = ['id' => $id];
            if (!auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])) $where['user_id'] = auth()->user()->id;
            $model = $this->getModel()->where($where)->first();

            if ($model->product_id != $request->product_id){
                $isProductInCart = Cart::where('product_id', $request->product_id)
                    ->where('user_id', $model->user_id)
                    ->where('id', '!=', $id)->first();
                if (!is_null($isProductInCart)) throw new \Exception('Gagal melakukan update keranjang, barang sudah ada dikeranjang anda yang lain, silahkan update keranjang yang sudah ada');
            }

            if (is_null($model)) throw new \Exception('Data tidak ditemukan');
            $product = Product::findOrFail($request->product_id);
            DB::beginTransaction();
            if ($model->product_id == $request->product_id){
                $gap = $request->qty - $model->qty;
                $product->stock -= $gap;
                if (!$product->save()) throw new \Exception("Gagal melakukan update stok produk");
                $model->qty += $gap;
            } else {
                Product::findOrFail($model->product_id)->restoreStock($model->qty);

                if ($product->stock - $request->qty < 0) throw new \Exception('Stok barang tidak cukup');
                $product->stock -= $request->qty;
                if (!$product->save()) throw new \Exception('Gagal update stok barang');
                $model->product_id = $request->product_id;
                $model->qty = $request->qty;
            }
            if (!$model->save()) throw new \Exception("Gagal melakukan update cart detail");

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $where = ['id' => $id];
            if (!auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])) $where['user_id'] = auth()->user()->id;
            $model = $this->getModel()->where($where)->first();
            if (is_null($model)) throw new \Exception('Data tidak ditemukan');
            $product = Product::ins()->where('id', $model->product_id)->first();
            if (is_null($product)) throw new \Exception('Produk tidak ditemukan');
            $product->restoreStock($model->qty);
            if (!$model->delete()) throw new \Exception("Gagal menghapus produk dari keranjang");
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }
}
