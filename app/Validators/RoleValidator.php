<?php
/*
 * Copyright (c) 7/12/21, 7:24 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Validators;


use App\Validators\Contracts\ValidatorContracts;

class RoleValidator implements ValidatorContracts
{
    public static function ins(): RoleValidator
    {
        return new self();
    }

    public function rules($id): array
    {
        $unique = $id ? ',' . $id . ',id' : '';
        return [
            'slug' => 'required|string|unique:roles,slug' . $unique,
            'name' => 'nullable|string',
        ];
    }
}
