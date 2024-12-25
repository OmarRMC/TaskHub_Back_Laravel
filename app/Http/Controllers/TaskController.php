<?php

namespace App\Http\Controllers;

use App\Mail\NotificationShipped;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $user = Auth::user();
        $tasks = $user->tasks()->latest()->paginate($limit);
        return view('task.index', compact('tasks', 'limit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task();
        return view('task.create', compact('task'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $task = new Task($validated);
        $task->user_id = Auth::user()->id;
        $task->save();
        return redirect()->route('task.index')->with('success', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $editing = true;
        return view('task.edit', compact('task', 'editing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $completed = $request->input('completed') ? 1 : 0;
        $task->completed = $completed;
        $task->update($validated);

        $user = Auth::user();
        $completedTask = $user->completedTasks()->count();

        if ($completedTask == 10) {
            Mail::to($user->email)->queue(new NotificationShipped($user->email, 'Congratulations', 'You have managed to complete 10 tasks, keep going'));
        }
        return redirect()->route('task.index')->with('success', 'Task updated successfully')->with('completed', 'Number of tasks completed : ' . $completedTask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index')->with('success', 'Task deleted successfully');
    }
}
