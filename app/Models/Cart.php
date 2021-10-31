<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use App\Validators\CartValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Cart extends BaseModel
{
    use HasFactory;
    protected $fillable = ['product_id', 'user_id', 'qty'];

    public static function ins(){
        return new self();
    }

    public function pop(Request $request)
    {
        return $this->fill([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'qty' => $request->qty
        ]);
    }

    public function getValidator()
    {
        return CartValidator::ins();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
