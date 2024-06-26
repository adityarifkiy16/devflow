<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = User::with('role')->get();
        $userCount = User::count();
        return view("dashboard.admindashboard", compact('users', 'userCount'));
    }
}
