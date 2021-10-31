<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers;

use App\Repositories\Contracts\ProductContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends BaseController
{
    protected $name = 'Product';

    public function __construct(ProductContract $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:sanctum')->except(['show', 'index']);
        $this->middleware('is.admin')->only(['store', 'update', 'delete']);
    }

    public function delete($id, Request $request)
    {
        try {
            $this->repository->removeWithLog($id, $request);
            return $this->json(
                Response::HTTP_ACCEPTED,
                "The $this->name $id was deleted.",
                []
            );
        } catch (\Exception $e) {
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }
}
