<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use App\Validators\ProductValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Product extends BaseModel
{
    use HasFactory;
    protected $fillable = ['name', 'stock', 'price'];

    public static function ins()
    {
        return new self();
    }

    public function pop(Request $request)
    {
        return $this->fill([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price
        ]);
    }

    public function getValidator()
    {
        return ProductValidator::ins();
    }

    public function restoreStock($stock){
        try {
            $this->stock += $stock;
            if (!$this->save()) throw new \Exception('Gagal mengembalikan stok barang');
            return $this;
        } catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }
}
