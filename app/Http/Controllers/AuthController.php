<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Login Screen, If user has session for Auth, it will be taken to dashboard
     *
     * @return void
     */
    public function index()
    {
        // if (Auth::check()) {
        //     return redirect(route('complaints'));
        // }
        return view('layout.auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        // print_r($request->input());die;
        $validated = $request->validated();

        $success = $this->repository->doLogin($request);

        if ($success) {
            alert()->success('Success', 'Success');
            return redirect(route('complaints'));
        }

        alert()->error('Authentication Failed', 'Error');
        return back();

    }

    public function logout()
    {
        $this->repository->doLogout();
        Auth::logout();
        alert()->success('Logged out successfully', 'Good Bye!');
        return redirect(route('login'));
    }
}
