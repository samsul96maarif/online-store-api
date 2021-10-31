<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'slug' => 'super-admin',
                'name' => 'Super Admin',
            ],
            [
                'slug' => 'admin',
                'name' => 'Admin',
            ],
            [
                'slug' => 'employee',
                'name' => 'Employee',
            ],
            [
                'slug' => 'user',
                'name' => 'User',
            ]
        ]);
    }
}
