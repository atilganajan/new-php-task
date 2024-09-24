<?php

namespace App\Http\Middleware;

class AuthMiddleware
{
    public function handle($next)
    {
        /* kontrol işlemleri yapılabilir */


        return $next();
    }
}