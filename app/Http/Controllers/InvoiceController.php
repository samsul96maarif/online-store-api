<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers;

use App\Repositories\Contracts\InvoiceContract;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    protected $name = 'Invoice';

    public function __construct(InvoiceContract $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:sanctum');
        $this->middleware('is.admin')->only(['update']);
    }
}
