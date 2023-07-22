<?php

namespace App\Http\Controllers;

use App\Events\TaskAssignEvent;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getTasks()
    {
        $tasks = Task::get();
        return TaskResource::collection($tasks);
    }


    function store(Request $request)
    {
        // VALIDATION 
        $request->validate([
            'title' => 'required|max:255|min:3',
            'details' => 'required|max:255|min:3',
            'user_id' => 'required|exists:users,id',
        ]);

        // STORE DATA
        $task = Task::create([
            'title' => $request->title,
            'details' => $request->details,
            'user_id' => $request->user_id,
            'status' => $request->status
        ]);

        // FOR SEND MAIL TO ASSIGN USER 
        event(new TaskAssignEvent($task));
        return new TaskResource($task);
    }


    function getTask($id)
    {

        $task = Task::findOrFail($id);
        return $task;
    }


    function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255|min:3',
            'details' => 'required|max:255|min:3',
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::findOrFail($id);
        $user_id = $task->user_id;
        $task->update([
            'title' => $request->title,
            'details' => $request->details,
            'user_id' => $request->user_id,
            'status' => $request->status
        ]);

        // IF NOT EXISTING USER THAN SEND A MAIL 
        if ($task->user_id != $user_id) {
            event(new TaskAssignEvent($task));
        }
        return true;
    }

    // Delete Task
    function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return true;
    }
}
