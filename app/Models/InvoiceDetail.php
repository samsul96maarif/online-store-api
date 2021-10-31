<?php

namespace App\Models;

use App\Validators\InvoiceDetailValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceDetail extends BaseModel
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'product_id', 'qty', 'price'];
    public static function ins()
    {
        return new self();
    }

    public function pop(Request $request)
    {
        return $this->fill([
            'invoice_id' => $request->invoice_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'price' => $request->price
        ]);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function getValidator()
    {
        return InvoiceDetailValidator::ins();
    }
}
