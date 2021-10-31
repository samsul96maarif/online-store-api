<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use App\Helpers\HelperTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ComLogDetail extends Model
{
    use HasFactory, HelperTrait;

    public static function ins(){
        return new self();
    }

    public function pop($com_log_id, $operation, $com_activity_table_id, $new_value = [], $old_value = [], $model_id = null, $sequence = 1){
        try {
            $oldValue = http_build_query($old_value) . "\n";
            $newValue = http_build_query($new_value) . "\n";
            return DB::table('com_log_details')->insert([
                'com_log_id' => $com_log_id,
                'com_activity_table_id' => $com_activity_table_id,
                'operation' => $operation,
                'model_id' => $model_id,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'sequence' => $sequence
            ]);
        }catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            throw $e;
        }

    }
}
