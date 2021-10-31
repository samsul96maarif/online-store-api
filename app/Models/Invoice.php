<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use App\Validators\InvoiceValidator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;

class Invoice extends BaseModel
{
    use HasFactory;
    protected $fillable = ['user_id', 'description', 'total', 'code'];
    public static function ins()
    {
        return new self();
    }

    public function pop(Request $request)
    {
        return $this->fill([
            'user_id' => auth()->user()->id,
            'description' => $request->description,
            'total' => $request->total,
            'code' => $request->code
        ]);
    }

    public function getValidator()
    {
        return InvoiceValidator::ins();
    }

    public function details(){
        return $this->hasMany(InvoiceDetail::class);
    }

    public function generateCode(){
        try {
            $currentTime = Carbon::now()->format('Ymdhis');
            $res = $currentTime.'1';
            $model = Invoice::where('code', 'like', $currentTime.'%')->orderBy('code', 'desc')->first();
            if (!is_null($model)) $res = (string)((int)$model->code+1);
            return $res;
        }catch (\Exception $e){
            throw $e;
        }
    }
}
