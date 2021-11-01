<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace Database\Seeders;

use App\Models\ComActivity;
use App\Models\ComActivityTable;
use App\Models\ComTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartLogModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $activities = ComActivity::where('name', 'like', '%Cart')->get();
            $cartTable = ComTable::where('name', 'carts')->firstOrFail();
            $productTable = ComTable::where('name', 'products')->firstOrFail();
            $tables = [$productTable, $cartTable];
            DB::beginTransaction();
            foreach ($activities as $a){
                for ($i=1; $i <= count($tables); $i++){
                    ComActivityTable::create([
                        'com_table_id' => $tables[$i-1]['id'],
                        'com_activity_id' => $a->id,
                        'sequence' => $i
                    ]);
                }
            }
            DB::commit();
            echo "Success \n";
        }catch (\Exception $e){
            DB::rollBack();
            echo "Failed ".$e->getMessage()." \n";
        }
    }
}
