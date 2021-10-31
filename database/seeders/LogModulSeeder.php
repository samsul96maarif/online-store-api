<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace Database\Seeders;

use App\Models\ComActivity;
use App\Models\ComTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            $arr = [
                'Create Product',
                'Update Product',
                'Delete Product',
                'Create Cart',
                'Update Cart',
                'Delete Cart',
                'Create Invoice',
                'Update Invoice',
                'Delete Invoice'
            ];
            $arr_2 = ['products', 'invoices', 'carts'];

            for ($i=0; $i<count($arr); $i++){
                ComActivity::create(['name' => $arr[$i]]);
            }
            for ($i=0; $i<count($arr_2); $i++){
                ComTable::create(['name' => $arr_2[$i]]);
            }

            $comActivityModel = ComActivity::where('name', 'like', "%Product")->get();
            $productTable = ComTable::where('name', 'products')->first();
            foreach ($comActivityModel as $c){
                $c->comTables()->attach($productTable);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            echo "Error ".$e->getMessage()." \n";
        }
    }
}
