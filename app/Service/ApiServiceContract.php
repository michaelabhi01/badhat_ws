<?php

namespace App\Service;

use Illuminate\Http\Request;

interface ApiServiceContract
{
    public function apiLogin(Request $request, $guard);
    public function apiLogout();
}
