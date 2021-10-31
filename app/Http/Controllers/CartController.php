<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers;

use App\Constant\CartConstant;
use App\Http\Requests\IndexRequest;
use App\Repositories\Contracts\CartContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends BaseController
{
    protected $name = 'Cart',
        $repository;

    public function __construct(CartContract $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:sanctum');
    }

    public function index(IndexRequest $request)
    {
        try {
            $data = $this->repository->list();
            return $this->json(
                Response::HTTP_OK,
                "$this->name Fetched.",
                $data
            );
        } catch (\Exception $e) {
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }
}
