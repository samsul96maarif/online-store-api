<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers;

use App\Helpers\HelperTrait;
use App\Http\Controllers\Traits\ResponseTrait;
use App\Http\Controllers\Traits\RestController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        ResponseTrait,
        HelperTrait,
        RestController;

    protected $request,
        $repository,
        $name = "Controller";
}
