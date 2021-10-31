<?php
/*
 * Copyright (c) 7/12/21, 7:18 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Models\Contracts;

use Illuminate\Http\Request;

interface BaseModelContract
{
    public static function ins();

    public function pop(Request $request);

    public function getValidator();
}
