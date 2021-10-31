<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers;

use App\Repositories\Contracts\ProductContract;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    protected $name = 'Product';

    public function __construct(ProductContract $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:sanctum')->except(['show', 'index']);
    }
}
