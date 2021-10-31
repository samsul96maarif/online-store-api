<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

trait HelperTrait
{
    protected $boundaries = "================================================================================================";

    public function makeLogInfo($data, $message = ""){
        $stringData = (gettype($data) == "array") ? http_build_query($data, "", "|") : $data;
        Log::info($message." : ");
        Log::info($stringData);
        Log::info($this->boundaries);
    }
}
