<?php
/*
 * Copyright (c) 7/12/21, 7:23 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Validators\Contracts;


interface ValidatorContracts
{
    public static function ins();

    public function rules($id): array;

}
