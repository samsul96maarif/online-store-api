<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers;

use App\Constant\RoleConstant;
use App\Helpers\HelperTrait;
use App\Http\Controllers\Traits\ResponseTrait;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    use HelperTrait, ResponseTrait;

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed'
            ]);
            DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'email' => $data['email']
            ]);
            if (!$user) throw new \Exception('Register Failed');
            $role = Role::where('slug', RoleConstant::USER)->firstOrFail();
            $user->roles()->attach($role);
            DB::commit();
            return $this->json(201, 'success', ['token' => 'Bearer '.$user->createToken('API Token')->plainTextToken]);
        }catch (\Exception $e){
            DB::rollBack();
            $this->makeLogInfo($e->getMessage());
            return $this->json(400, $e->getMessage(), []);
        }
    }

    public function login(Request $request)
    {
        try {
            $attr = $request->validate([
                'email' => 'required|string|email|',
                'password' => 'required|string|min:6'
            ]);
            if (!Auth::attempt($attr)) throw new \Exception('Credentials not match');
            return $this->json(200, 'success', ['token' => 'Bearer '.auth()->user()->createToken('API Token')->plainTextToken]);
        }catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            return $this->json(400, $e->getMessage(), []);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->json(202, 'Tokens Revoked', []);
        }catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            return $this->json(400, $e->getMessage(), []);
        }

    }
}
