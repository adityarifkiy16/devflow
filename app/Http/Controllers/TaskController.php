<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status_id' => 'required|exists:mstatuses,id',
            'project_id' => 'required|exists:mprojects,id',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $data = $request->validate($rules, $customMessages);

        Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status_id' => $data['status_id'],
            'project_id' => $data['project_id'],
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Task created successfuly'
        ]);
    }
}
