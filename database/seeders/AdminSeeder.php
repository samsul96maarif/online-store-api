<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace Database\Seeders;

use App\Constant\RoleConstant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
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
            $admin = User::create([
                'name' => 'admin',
                'password' => bcrypt('123456'),
                'email' => 'admin@evermos.com'
            ]);
            $user = User::create([
                'name' => 'user',
                'password' => bcrypt('123456'),
                'email' => 'user@evermos.com'
            ]);
            if (!$admin) throw new \Exception('Register Failed');
            if (!$user) throw new \Exception('Register Failed');
            $adminRole = Role::where('slug', RoleConstant::ADMIN)->firstOrFail();
            $userRole = Role::where('slug', RoleConstant::USER)->firstOrFail();
            $admin->roles()->attach($adminRole);
            $user->roles()->attach($userRole);
            DB::commit();
            echo "Succeed \n";
        }catch (\Exception $e){
            DB::rollBack();
            echo "Failed ".$e->getMessage()." \n";
        }
    }
}
