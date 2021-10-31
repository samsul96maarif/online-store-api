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
            return $this->json(400, $e->getMessage());
        }
    }

    public function updateDetail(Request $request)
    {
        try {
            $res = $this->repository->modifyDetail($request, CartConstant::UPDATE_ACTION);
            return $this->json(
                Response::HTTP_ACCEPTED,
                "Update keranjang berhasil.",
                $res);
        }catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            return $this->json(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function deleteDetail(Request $request)
    {
        try {
            $res = $this->repository->modifyDetail($request, CartConstant::DELETE_ACTION);
            return $this->json(
                Response::HTTP_ACCEPTED,
                "menghapus produk dari keranjang berhasil.",
                $res);
        }catch (\Exception $e){
            $this->makeLogInfo($e->getMessage());
            return $this->json(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function getDetails($id)
    {
        try {
            return $this->json(
                Response::HTTP_OK,
                "get Detail List",
                $this->repository->detail($id)
            );
        } catch
        (\Exception $e) {
            throw $e;
        }
    }
}
