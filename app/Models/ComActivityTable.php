<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ComActivityTable extends Model
{
    use HasFactory;

    public function comActivity(){
        return $this->belongsTo(ComActivity::class);
    }

    public function comTable(){
        return $this->belongsTo(ComTable::class);
    }

    public static function findByTableAndActivity($table_name, $activity_name)
    {
        try {
            $res = DB::table('com_activity_tables')
                ->selectRaw('com_activity_tables.*')
                ->join('com_activities', 'com_activity_tables.com_activity_id', '=', 'com_activities.id')
                ->join('com_tables', 'com_activity_tables.com_table_id', '=', 'com_tables.id')
                ->where([
                    'com_activities.name' => $activity_name,
                    'com_tables.name' => $table_name
                ])->first();
            if (is_null($res)) throw new \Exception("Data not found!");
            return $res;
        }catch (\Exception $e){
            throw $e;
        }
    }
}
