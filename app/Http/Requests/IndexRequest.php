<?php
/*
 * Copyright (c) 7/12/21, 10:14 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => 'nullable|string|min:1',
            'page' => 'nullable|string|min:1',
            'filter' => 'nullable|string',
            'sort' => 'nullable|string|min:3',
            'q' => 'nullable|string|min:0',
        ];
    }
}
