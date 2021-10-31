<?php
/*
 * Autrhor: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Controllers\Traits;

use App\Http\Requests\IndexRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Handle requiring restful
 * Trait RestfulController
 * @package App\Http\Controllers\Traits
 */
trait RestController
{
    public function resource()
    {
        return [];
    }

    /**
     * @param IndexRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function index(IndexRequest $request)
    {
        try {
            $data = $this->repository->fetch($request);
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

    public function list()
    {
        try {
            $data = $this->repository->list();
            return $this->json(
                Response::HTTP_OK,
                "$this->name Fetched.",
                $data
            );
        }catch (\Exception $e){
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function show($id, Request $request)
    {
        try {
            $dataById = $this->repository->byId($id) ?? [];
            return $this->json(
                    Response::HTTP_OK,
                    "$this->name Fetched.",
                    $dataById
                );
        } catch (\Exception $e) {
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            $data = $this->repository->store($request);
            return $this->json(
                Response::HTTP_CREATED,
                "$this->name Saved Successfully.",
                $data
            );
        } catch (\Exception $e) {
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function update($id, Request $request)
    {
        try {
            $data = $this->repository->update($id, $request);

            return $this->json(
                    Response::HTTP_ACCEPTED,
                    "$this->name Updated Successfully.",
                    $data
                );
        } catch (\Exception $e) {
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $this->repository->remove($id);
           return $this->json(
                    Response::HTTP_NO_CONTENT,
                    "The $this->name $id was deleted.",
                    []
                );
        } catch (\Exception $e) {
            $code = ($e->getCode() > 100 && $e->getCode() < 599) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            return $this->json($code, $e->getMessage(), []);
        }
    }
}
