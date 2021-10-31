<?php
/*
 * Copyright (c) 7/12/21, 9:40 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Helpers\HelperTrait;
use App\Repositories\Traits\RestfulRepository;

abstract class Repository
{
    use RestfulRepository, HelperTrait;

    protected
        $sortBy = ['created_at'];
}
