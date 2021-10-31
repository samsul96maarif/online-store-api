<?php
/*
 * Copyright (c) 7/13/21, 7:55 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Constant\RoleConstant;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Contracts\CartContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartRepository extends Repository implements CartContract
{
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
            $data = $this
                ->getModel()
                ->where([
                    'id' => $id,
                    'user_id' => auth()->user()->id
                ])
                ->with('product')
                ->first();
            if (!$data) throw new \Exception("Data not found");
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store($request)
    {
        try {
            if(is_null($request->product_id)) throw new \Exception("Pilih produk terlebih dahulu!");
            $product = Product::findOrFail($request->product_id);
            $request->request->add(['user_id' => auth()->user()->id]);

            $model = Cart::where([
                'product_id' => $request->product_id,
                'user_id' => auth()->user()->id
            ])->first();

            DB::beginTransaction();
            if ($product->stock - $request->qty < 0) throw new \Exception('Stok barang tidak cukup');
            $product->stock -= $request->qty;
            if (!$product->save()) throw new \Exception('Gagal update stok barang');
            if (is_null($model)){
                $model = $this->getModel()->pop($request);
            } else {
                $model->qty += $request->qty;
            }
            if (!$model->save()) throw new \Exception("Gagal menambah barang ke dalam keranjang");
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
            $model = $this->getModel()->where([
                'user_id' => auth()->user()->id,
                'id' => $id
            ])->first();
            if (is_null($model)) throw new \Exception('Data tidak ditemukan');
            $product = Product::findOrFail($request->product_id);
            DB::beginTransaction();
            if ($model->product_id == $request->product_id){
                $gap = $request->qty - $model->qty;
                $product->stock -= $gap;
                if (!$product->save()) throw new \Exception("Gagal melakukan update stok produk");
                $model->qty += $gap;
            } else {
                $product->restoreStock($model->qty);
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
            $model = $this->getModel()->where([
                'id' => $id,
                'user_id' => auth()->user()->id
            ])->first();
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
