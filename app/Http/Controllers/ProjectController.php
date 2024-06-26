<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('user')->paginate(5);
        return view('project.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $data = $request->validate($rules, $customMessages);
        Project::create([
            'name' => $data['name'],
            'user_id' => Auth::user()->id
        ]);
        return response()->json(['code' => 200, 'message' => 'Project has been created successfully.']);
    }

    public function kanban($id)
    {
        $project = Project::findOrFail($id);
        $tasks = $project->task;
        $statuses = Status::whereIn('slug', ['to-do', 'in-progress', 'done'])->get();
        return view('project.kanban', compact('project', 'tasks', 'statuses'));
    }
}
