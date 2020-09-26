<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Admin;

class AuthController extends Controller
{
    public function login(Request $request)
    {   
        if($request->isMethod('post')) {
            // print_r($request->input());die;
            $this->validator($request);

            if($request->email=='admin@badhat.app'){

            	if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
			      		alert()->success('Success', 'Success');
			                // echo "ew"; die;
			            return redirect(route('dashboard'));
			    }
            }
 			alert()->error('Authentication Failed', 'Error');
            return back();  
        }
        else {
            if(Auth::check()){
                return redirect(route('dashboard'));
            }
            else{
                return view('layout.auth.login');
            }
        }
        
    }

    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:users|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules, $messages);
    }


    public function logout()
    {
        // Auth::guard('admin')->logout();
        Auth::logout();
        alert()->success('Logged out successfully', 'Good Bye!');
        return redirect(route('login'));
    }
}
