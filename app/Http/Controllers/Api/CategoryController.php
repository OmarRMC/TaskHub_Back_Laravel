<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query->get('limit') ?? 10;
        $categories = $request->user()->categories()->paginate($limit);
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->user_id = $request->user()->id;
        $category->save();

        return response()->json([
            'message' => 'a new category was created successfully',
            'data' => new CategoryResource($category)
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        if ($category->user_id === $request->user()->id) {
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            return response()->json([
                'message' => 'Category updated successfully.',
                'data' => new CategoryResource($category),
            ], 200);
        } else {
            throw new ModelNotFoundException('Category  not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,  Category $category)
    {
        $userId = $request->user()->id;
        if ($category->user_id === $userId) {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } else {
            throw new ModelNotFoundException('category not found');
        }
    }
}
