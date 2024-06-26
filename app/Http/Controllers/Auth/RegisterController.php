<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'username' => ['required', 'unique:users,username'],
            'nomer_telp' => ['required', 'numeric', 'digits_between:1,16'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        $data = $request->all();
        $data['unid'] = Str::uuid()->toString();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if ($user) {
            return response()->json([
                'code' => 200,
                'message' => 'success registration',
            ], 200);
        }

        return response()->json([
            'code' => 409,
            'message' => 'there is conflict data'
        ], 409);
    }
}
