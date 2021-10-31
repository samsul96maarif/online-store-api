<?php
/*
 * Copyright (c) 7/24/21, 9:09 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Trait HasRole
 * @package App\Models\Traits
 */
trait ResponseTrait
{
    public function json($code = Response::HTTP_OK, $message = '', $data = null)
    {
        if (!is_null($data)) {
            if (method_exists($data, 'items')) {

                $request = app(Request::class);

                $query = [];
                foreach ($request->query() as $key => $value) {
                    if ($key != 'page')
                        $query[] = $key . '=' . $value;
                }

                $query = '&' . implode('&', $query);

                $previousPageUrl = (!empty($data->previousPageUrl())) ?
                    $data->previousPageUrl() . $query :
                    $data->previousPageUrl();

                $nextPageUrl = (!empty($data->nextPageUrl())) ?
                    $data->nextPageUrl() . $query :
                    $data->nextPageUrl();


                $currentPageUrl = (!empty($data->url($data->currentPage()))) ?
                    $data->url($data->currentPage()) . $query :
                    $data->url($data->currentPage());

                $paginate = [
                    'has_more_pages' => $data->hasMorePages(),
                    'count' => (int)$data->count(),
                    'total' => (int)$data->total(),
                    'per_page' => (int)$data->perPage(),
                    'current_page' => (int)$data->currentPage(),
                    'last_page' => (int)$data->lastPage(),
                    'prev_page_url' => $previousPageUrl,
                    'current_page_url' => $currentPageUrl,
                    'next_page_url' => $nextPageUrl
                ];

                return response()->json(
                    [
                        "code" => $code,
                        "message" => $message,
                        "data" => $data->items(),
                        "pagination" => $paginate
                    ], $code, []
                );
            }

            return response()->json(
                [
                    "code" => $code,
                    "message" => $message,
                    "data" => $data
                ], $code, []
            );
        }
        return response()->json(
            [
                "code" => $code,
                "message" => $message
            ], $code, []
        );
    }
}
