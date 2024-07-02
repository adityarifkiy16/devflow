<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::whereIn('name', ['PM', 'Tester', 'Developer'])->get();
        $users = User::with('role')->get();
        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'nomer_telp' => 'required|numeric|min:11',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'email' => 'required|email|unique:musers|max:255',
            'password' => 'required|min:6',
            'role' => 'required|exists:mroles,id'
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password harus minimal :min karakter.',
            'password.max' => 'Password maksimal :max karakter.',
            'role.exists' => 'Role yang dipilih tidak valid.'
        ];

        $data = $request->validate($rules, $customMessages);
        $data['password'] = Hash::make($data['password']);
        $data['unid'] = Str::uuid();
        User::create([
            'name' => $data['name'],
            'unid' => $data['unid'],
            'username' => $data['username'],
            'nomer_telp' => $data['nomer_telp'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role']
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Successfuly create user'
        ]);
    }

    public function edit(string $id)
    {
        $user = User::findorFail($id);
        $roles = Role::whereIn('name', ['PM', 'Tester', 'Developer'])->get();
        return response()->json(['code' => 200, 'data' => ['user' => $user, 'roles' => $roles]], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::findorFail($id);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:musers,email,' . $user->id,
            'password' => 'required|min:6',
            'role' => 'required|exists:mroles,id'
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password harus minimal :min karakter.',
            'password.max' => 'Password maksimal :max karakter.',
            'role.exists' => 'Role yang dipilih tidak valid.'
        ];

        $data = $request->validate($rules, $customMessages);
        $data['password'] = Hash::make($data['password']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role'];
        $user->password = $data['password'];
        $user->save();

        return response()->json(['code' => 200, 'message' => 'successfully update user!']);
    }

    public function destroy($id)
    {
        $user = User::findorFail($id);
        $user->delete();
        return response()->json([
            'code' => 200,
            'message' => 'Successfuly deleted user'
        ]);
    }
}
