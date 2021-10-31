<?php
/*
 * Copyright (c) 7/12/21, 7:15 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Models\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * Trait HasRole
 * @package App\Models\Traits
 */
trait HasRole
{
    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_users',
            'user_id',
            'role_id');
    }

    public function getByRoles(array $roles)
    {
        $users = $this::all();

        return $users->filter(function ($user) use ($roles) {
            foreach ($roles as $roleName) {
                if ($user->hasRole($roleName))
                    return $user;
            }
        });
    }

    /**
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {

        if (\is_string($roles) && false !== \strpos($roles, '|')) {
            $roles = \explode('|', $roles);
        }

        if (\is_string($roles) || $roles instanceof Role) {
            return $this->roles->contains('name', $roles->slug ?? $roles);
        }

        $roles = \collect()->make($roles)->map(function ($role) {
            return $role instanceof Role ? $role->slug : $role;
        });

        return !$roles->intersect($this->roles->pluck('slug'))->isEmpty();
    }

    /**
     * @param $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function getByRoleName($roleName)
    {
        return DB::table('users')
            ->select('users.id', 'users.name', 'users.username', 'roles.name')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('roles.slug', '=', strtoupper($roleName))
            ->get();
    }

}
