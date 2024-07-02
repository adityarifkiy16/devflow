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
            'description' => 'max:255',
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

    public function updateTaskStatus(Request $request)
    {
        $rules = [
            'status_id' => 'required|exists:mstatuses,id',
            'tasks.*.id' => 'required|exists:mtasks,id',
            'tasks.*.order' => 'required|integer',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $data = $request->validate($rules, $customMessages);
        foreach ($data['tasks'] as $taskData) {
            $task = Task::find($taskData['id']);
            $task->status_id = $data['status_id'];
            $task->order = $taskData['order'];
            $task->save();
        }
        return response()->json(['code' => 200, 'message' => 'Status Task updated']);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'max:255',
            'task_id' => 'required|exists:mtasks,id',
            'status_id' => 'required|exists:mstatuses,id',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $data = $request->validate($rules, $customMessages);
        $task = Task::findOrFail($id);
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->status_id = $data['status_id'];
        $task->save();
        return response()->json(['code' => 200, 'message' => 'Tasks updated successfully.']);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['code' => 200, 'message' => 'Task deleted successfully']);
    }
}
