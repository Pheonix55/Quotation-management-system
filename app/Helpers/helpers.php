<?php

use App\Models\User;

if (!function_exists('adminUser')) {
    function adminUser()
    {
        return User::where('isCustomer', '0')->first();
    }
}
