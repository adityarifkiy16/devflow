<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.sign-in');
    }

    public function login(Request $request)
    {
        $rules = [
            'login' => 'required|string',
            'password' => 'required|string|min:8',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $credentials = $request->validate($rules, $customMessages);

        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'code' => 200,
                'message' => 'Login Success',
                'data' => [
                    'user' => $user,
                ]
            ], 200);
        }
        return response()->json([
            'code' => 401,
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }
}
