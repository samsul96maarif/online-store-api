<?php
/*
 * Copyright (c) 7/12/21, 9:33 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Constant;

class InvoiceConstant
{
    const
        EMPLOYEE_TABLE = 'employees',
        GUEST_TABLE = 'guests',
        USERS_TABLE = 'users',
        ADMIN_TABLE = 'users';

    const
        STATUS_AWAITING = 'awaiting',
        STATUS_PAID = 'paid',
        STATUS_PROCESSING = 'processing',
        STATUS_DONE = 'done';
}
