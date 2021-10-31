<?php
/*
 * Copyright (c) 7/12/21, 7:24 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Validators;

use App\Validators\Contracts\ValidatorContracts;

class CartValidator implements ValidatorContracts
{
    public static function ins()
    {
        return new self();
    }

    public function rules($id): array
    {
        return [
            'user_id' => 'required',
            'qty' => 'min:1|number|required',
            'product_id' => 'required'
        ];
    }
}
