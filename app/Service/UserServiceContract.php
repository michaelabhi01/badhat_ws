<?php

namespace App\Service;

use Illuminate\Http\Request;

interface UserServiceContract
{
    public function doLogin(Request $request);
    public function doLogout();

    public function getAllUsers();
}
