<?php
/*
 * Copyright (c) 7/12/21, 7:24 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Validators;


use App\Validators\Contracts\ValidatorContracts;

class ProductValidator implements ValidatorContracts
{
    public static function ins(): ProductValidator
    {
        return new self();
    }

    public function rules($id): array
    {
        return [
            'name' => 'nullable|string',
            'stock' => 'min:0|number',
            'price' => 'number|min:0'
        ];
    }
}
