<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use App\Validators\CartDetailValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CartDetail extends BaseModel
{
    use HasFactory;
    protected $fillable = ['cart_id', 'product_id', 'qty', 'price'];

    public static function ins(){
        return new self();
    }

    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function pop(Request $request)
    {
        return $this->fill([
            'cart_id' => $request->cart_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'product_id' => $request->product_id
        ]);
    }

    public function getValidator()
    {
        return CartDetailValidator::ins();
    }
}
