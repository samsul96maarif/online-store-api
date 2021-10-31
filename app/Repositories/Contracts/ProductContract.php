<?php
/*
 * Copyright (c) 7/13/21, 7:58 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryContract;
use Illuminate\Http\Request;

interface ProductContract extends RepositoryContract
{
    public function removeWithLog($id, Request $request);
}
