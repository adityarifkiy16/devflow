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
        $statuses = Status::whereIn('slug', ['done', 'in-progress', 'production', 'to-do'])
            ->get()
            ->keyBy('slug')
            ->map(function ($status) {
                return $status->id;
            });

        $projects = Project::with('user')
            ->withCount([
                'tasks',
                'tasks as done_tasks_count' => function ($query) use ($statuses) {
                    $query->where('status_id', $statuses['done']);
                },
                'tasks as prod_tasks_count' => function ($query) use ($statuses) {
                    $query->where('status_id', $statuses['production']);
                },
                'tasks as prog_tasks_count' => function ($query) use ($statuses) {
                    $query->where('status_id', $statuses['in-progress']);
                },
                'tasks as todo_tasks_count' => function ($query) use ($statuses) {
                    $query->where('status_id', $statuses['to-do']);
                }
            ])->paginate(5);

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

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json(['code' => 200, 'message' => 'Project deleted successfully']);
    }
}
