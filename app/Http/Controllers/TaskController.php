<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Image;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'max:255',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'status_id' => 'required|exists:mstatuses,id',
            'project_id' => 'required|exists:mprojects,id',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
            'file.mimes' => 'Only jpg, jpeg, png, and gif files are allowed',
            'file.max' => 'Maximum file size is 2MB',
        ];

        $data = $request->validate($rules, $customMessages);

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status_id' => $data['status_id'],
            'project_id' => $data['project_id'],
            'user_id' => Auth::user()->id
        ]);

        if (isset($data['files'])) {
            $files = $data['files'];
            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/uploads/taskimages', $fileName);
                Image::create([
                    'path' =>  $path,
                    'task_id' => $task->id
                ]);
            }
        }

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

    public function edit(Request $request, $id)
    {
        $task = Task::findorFail($id);
        $file = Image::where('task_id', $id)->get();
        $statuses = Status::all();
        return response()->json(['code' => 200, 'task' => $task, 'statuses' => $statuses, 'file' => $file]);
    }

    public function update(Request $request, $id)
    {
        // dd($request);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'max:255',
            'task_id' => 'required|exists:mtasks,id',
            'status_id' => 'required|exists:mstatuses,id',
            'files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $data = $request->validate($rules, $customMessages);

        $task = Task::findOrFail($id);
        $task->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'status_id' => $data['status_id'],
        ]);

        if (isset($data['files'])) {
            $files = $data['files'];
            $existFiles = Image::where('task_id', $data['task_id'])->get();
            foreach ($existFiles as $existFile) {
                Storage::delete($existFile->path);
                $existFile->delete();
            }
            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/uploads/taskimages', $fileName);
                Image::create([
                    'path' => $path,
                    'task_id' => $task->id,
                ]);
            }
        }
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
