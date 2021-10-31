<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use App\Helpers\HelperTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComLog extends Model
{
    use HasFactory, HelperTrait;

    public function details(){
        return $this->hasMany(ComLogDetail::class);
    }

    public function comActivity(){
        return $this->belongsTo(ComActivity::class);
    }

    public static function ins(){
        return new self();
    }

    public function pop($com_activity_name, Request $request){
        try {
            $comActivityModel = ComActivity::where('name', $com_activity_name)->firstOrFail();
            if (is_null($comActivityModel)) throw new \Exception('Activity not found');
            $comLogId = DB::table('com_logs')->insertGetId([
                'com_activity_id' => $comActivityModel->id,
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return $comLogId;
        }catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }
    }
}
