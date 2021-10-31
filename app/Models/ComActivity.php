<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComActivity extends Model
{
    use HasFactory;

    public function comTables()
    {
        return $this->belongsToMany(
            ComTable::class,
            'com_activity_tables',
            'com_activity_id',
            'com_table_id');
    }
}
