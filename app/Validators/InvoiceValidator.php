<?php
/*
 * Copyright (c) 7/12/21, 7:24 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Validators;


use App\Constant\InvoiceConstant;
use App\Validators\Contracts\ValidatorContracts;

class InvoiceValidator implements ValidatorContracts
{
    public static function ins()
    {
        return new self();
    }

    public function rules($id): array
    {
        $unique = $id ? ',' . $id . ',id' : '';
        return [
            'code' => 'required|string|company_unique:invoices,code'.$unique,
            'date' => 'required',
            'company_id' => 'required',
            'writer_id' => 'required',
            'customer_id' => 'required',
            'writer_table' => 'required|in:'.InvoiceConstant::ADMIN_TABLE.','.InvoiceConstant::EMPLOYEE_TABLE,
            'customer_table' => 'required|in:'.InvoiceConstant::GUEST_TABLE.','.InvoiceConstant::USERS_TABLE,
            'total' => 'required|number',
            'status' => 'required|in:'.InvoiceConstant::STATUS_AWAITING.','.InvoiceConstant::STATUS_PAID,
            'process_status' => 'in:'.InvoiceConstant::STATUS_PROCESSING.','.InvoiceConstant::STATUS_DONE
        ];
    }
}
