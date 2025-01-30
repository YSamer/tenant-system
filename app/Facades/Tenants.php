<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Tenants extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tenantService';
    }
}
