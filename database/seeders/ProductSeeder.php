<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace Database\Seeders;

use App\Constant\RoleConstant;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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
            $products = ['jeruk', 'apel', 'mangga', 'strawbery'];
            for ($i=0; $i < count($products); $i++){
                $model = Product::create([
                    'name' => $products[$i],
                    'price' => rand(1000, 5000),
                    'stock' => rand(10, 30)
                ]);
                if (!$model) throw new \Exception('Gagal');
            }
            DB::commit();
            echo "Succeed \n";
        }catch (\Exception $e){
            DB::rollBack();
            echo "Failed ".$e->getMessage()." \n";
        }
    }
}
