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

    protected $name = 'Repository', $create_activity_name = null,
        $update_activity_name = null, $delete_activity_name = null,
        $using_log_modul = false,
        $order_by = 'asc',
        $sortBy = 'created_at';
}
