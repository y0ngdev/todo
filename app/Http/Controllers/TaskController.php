<?php

namespace App\Http\Controllers;

use App\Events\TaskAdded;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function all()
    {
        $tasks = Task::all();
        return response()->json([
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        $task = Task::create($request->only('name'));
        broadcast(new TaskAdded($task));
        return $task;
    }

    public function update(Request $request, Task $task): Task
    {
        $task->update($request->only('name', 'completed'));
        broadcast(new TaskUpdated($task));
        return $task;
    }

    public function destroy(Task $task): \Illuminate\Http\JsonResponse
    {

        broadcast(new TaskDeleted($task->id));
//        $task->delete();
        return response()->json(['status' => 'Task deleted']);
    }
}
