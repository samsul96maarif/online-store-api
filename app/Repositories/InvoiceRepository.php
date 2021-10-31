<?php
/*
 * Copyright (c) 7/13/21, 7:55 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Constant\InvoiceConstant;
use App\Constant\RoleConstant;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Repositories\Contracts\InvoiceContract;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceRepository extends Repository implements InvoiceContract
{
    public function getModel()
    {
        return Invoice::ins();
    }

    public function fetch(Request $request)
    {
        try {
            $data = $this->getModel();
            $privilege = (\auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN]));
            if (!$privilege) $data = $data->where('user_id', auth()->user()->id);
            return $data->paginate($request->per_page);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function list()
    {
        try {
            $data = DB::table('invoices');
            $privilege = (\auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN]));
            if (!$privilege) $data = $data->where('user_id', auth()->user()->id);
            return $data->get();
        }catch (\Exception $e){
            throw $e;
        }
    }

    public function byId($id)
    {
        try {
            $where = ['id' => $id];
            if(!\auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])) $where['user_id'] = auth()->user()->id;
            $data = $this->getModel()->where($where)->with(['details' => function ($q) {
                return $q->with('product');
            }]);
            return $data->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store($request)
    {
        try {
            if (is_null($request->details)) throw new \Exception('Keranjang masih kosong');
            $request->request->add([
                'code' => $this->getModel()->generateCode(),
                'total' => 0
            ]);

            DB::beginTransaction();

            $result = $this->getModel()->pop($request);
            if (!$result->save()) throw new \Exception("Data stored unsuccessfully. ".$result->errors);

            foreach ($request->details as $d){
                if(is_null($d['cart_id'])) throw new \Exception("Keranjang tidak ditemukan");
                $cart = Cart::where([
                    'id' => $d['cart_id'],
                    'user_id' => auth()->user()->id
                ])->first();
                if (is_null($cart)) throw new \Exception("Keranjang tidak ditemukan");
                $product = Product::findOrFail($cart->product_id);
                $detail = InvoiceDetail::ins()->fill([
                    'price' => $product->price,
                    'invoice_id' => $result->id,
                    'qty' => $cart->qty,
                    'product_id' => $product->id
                ]);
                if (!$detail->save()) throw new \Exception("Storing detail failed");
                if (!$cart->delete()) throw new \Exception("Gagal memindahkan keranjang ke checkout");
            }

            $invDetail = DB::table('invoice_details')
                ->select(DB::raw('sum(qty * price) total'))
                ->where('invoice_id', $result->id)
                ->groupBy('invoice_id')->first();

            $result->total = is_null($invDetail) ? 0 : $invDetail->total;
            if (!$result->save()) throw new \Exception("Data stored unsuccessfully. ".$result->errors);

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        try {
            if (is_null($request->status)) throw new \Exception('Status harus diisi!');
            $result = $this->byId($id);
            $result->status = $request->status;
            if (!$result->save()) throw new \Exception("Data updated unsuccessfully. ".$result->errors);
            return $result;
        } catch (\Exception $e) {
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }

    public function _addDetail(Invoice $model, Request $data)
    {
        try {
            if(is_null($data->cart_id)) throw new \Exception("Keranjang tidak ditemukan");
            $cart = Cart::where([
                'id' => $data->cart_id,
                'user_id' => auth()->user()->id
            ])->first();
            if (is_null($cart)) throw new \Exception("Keranjang tidak ditemukan");
            $product = Product::findOrFail($cart->product_id);
            $detail = InvoiceDetail::ins()->fill([
                'price' => $product->price,
                'invoice_id' => $model->id,
                'qty' => $cart->qty,
                'product_id' => $product->id
            ]);
            if (!$detail->save()) throw new \Exception("Storing detail failed");
            return $detail;
        } catch (\Exception $e) {
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->getModel()->findOrFail($id);
            foreach ($result->details as $d){
                Product::findOrFail($d->product_id)->restoreStock($d->qty);
                if (!$d->delete()) throw new \Exception('Gagal menghapus detail checkout');
            }
            if (!$result->delete()) throw new \Exception("Data deleted unsuccessfully");
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
