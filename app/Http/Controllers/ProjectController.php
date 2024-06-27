<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        // Misalkan status 'done' memiliki id 1 dan 'todo' memiliki id 2
        $doneStatusId = 3;
        $progressId = 2;
        $prodId = 5;
        $todoStatusId = 1;

        // Mengambil data proyek dengan informasi pengguna terkait dan paginasi
        $projects = Project::with('user')
            ->withCount([
                'tasks',
                'tasks as done_tasks_count' => function ($query) use ($doneStatusId) {
                    $query->where('status_id', $doneStatusId);
                },
                'tasks as prod_tasks_count' => function ($query) use ($prodId) {
                    $query->where('status_id', $prodId);
                },
                'tasks as prog_tasks_count' => function ($query) use ($progressId) {
                    $query->where('status_id', $progressId);
                },
                'tasks as todo_tasks_count' => function ($query) use ($todoStatusId) {
                    $query->where('status_id', $todoStatusId);
                }
            ])
            ->paginate(5);

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
        $statuses = Status::whereIn('slug', ['to-do', 'in-progress', 'done', 'production'])->get();
        return view('project.kanban', compact('project', 'tasks', 'statuses'));
    }
}
