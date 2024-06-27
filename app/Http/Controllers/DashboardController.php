<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = User::with('role')->get();
        $taskCount = Task::count();
        $userCount = User::count();
        $projectCount = Project::count();
        return view("dashboard.admindashboard", compact('users', 'userCount', 'taskCount', 'projectCount'));
    }
}
