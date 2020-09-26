<?php

namespace App\Service;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RepositoryService implements RepositoryServiceContract
{

    public function doLogin(Request $request)
    {
        // check if user exist with email id
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return false;
        }

        // echo json_encode($user); die;
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::validate($credentials)) {
            Auth::attempt($credentials);
            return true;
        } else {
            return false;

        }
        return false;
    }

    public function doLogout()
    {
        // clear data or other operations needed

    }

    public function getAllUsers()
    {
        return User::with('role')
            ->with('department')
            ->whereHas('department');
    }

    // API

    public function apiLogin(Request $request, $guard)
    {        
        $credentials = $request->only(['mobile','otp']);
        JWTAuth::factory()->setTTL(60 * 720 * 60);
        if (!$token = $guard->attempt($credentials)) {
            return null;
        }

        $user = User::select('id','name','mobile', 'business_name','image')
            ->where('mobile', $request->mobile)
            ->first();

        return [$token, $user];
    }

    public function apiLogout()
    {
        JWTAuth::invalidate(JWTAuth::parseToken());
    }

}
