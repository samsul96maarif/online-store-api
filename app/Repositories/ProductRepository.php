<?php
/*
 * Copyright (c) 7/13/21, 7:55 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductContract;

class ProductRepository extends Repository implements ProductContract
{
    public function getModel()
    {
        return new Product();
    }
}
