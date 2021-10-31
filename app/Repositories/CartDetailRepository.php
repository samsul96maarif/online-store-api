<?php
/*
 * Copyright (c) 7/13/21, 7:55 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Repositories;

use App\Models\CartDetail;
use App\Repositories\Contracts\CartDetailContract;

class CartDetailRepository extends Repository implements CartDetailContract
{
    public function getModel()
    {
        return new CartDetail();
    }
}
