<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query->get('limit') ?? 15;
        $tags = $request->user()->tags()->paginate($limit);
        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $user = $request->user();
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->user()->associate($user);
        $tag->save();
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $userId = $request->user()->id;
        if ($tag->user_id !== $userId) {
            throw new ModelNotFoundException('tag  not found');
        }
        $tag->name = $request->name;
        $tag->save();
        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Tag $tag)
    {
        $userId = $request->user()->id;
        if ($tag->user_id !== $userId) {
            throw new ModelNotFoundException('tag not found');
        }
        $tag->delete();
        return response()->json([
            'message' => 'tag deleted successfully.',
        ], 200);
    }
}
