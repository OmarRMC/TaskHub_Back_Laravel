<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $category = $request->query('category');
        $tags = $request->query('tags');
        $tagsArray = $tags ? explode(',', $tags) : [];
        $query = Task::query()->where('user_id', $userId);
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }
        if (!empty($tagsArray)) {
            $query->whereHas('tags', function ($q) use ($tagsArray) {
                $q->whereIn('name', $tagsArray);
            });
        }
        $limit = $request->query->get('limit') ?? 15;
        $tasks = $query->with(['category', 'tags'])->paginate($limit);
        return  TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => ['required', 'exists:categories,id,user_id,' . $user->id],
            'tags' => [ 'array'],
            'tags.*' => [
                'integer',
                Rule::exists('tags', 'id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }),
            ]
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->category_id = $request->category_id;
        $task->completed = 0; 
        $task->user()->associate($user);
        $task->save();
        $task->tags()->sync($request->input('tags'), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        if ($task->user_id === $request->user()->id) {
            return new TaskResource($task);
        } else {
            throw new ModelNotFoundException('Task not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $user = $request->user();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => ['required', 'exists:categories,id,user_id,' . $user->id],
            'tags' => ['array'],
            'tags.*' => [
                'integer',
                Rule::exists('tags', 'id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }),
            ]
        ]);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->category_id = $request->category_id;
        $task->completed = $request->completed;
        $task->user()->associate($user);
        $task->tags()->sync($request->input('tags'), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $task->update(); 
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,  Task $task)
    {
        if ($task->user_id === $request->user()->id) {
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully.',
            ], 200);
        } else {
            throw new ModelNotFoundException('Task not found');
        }
    }
}
