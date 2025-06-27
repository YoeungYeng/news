<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        try {
            // index method to retrieve all categories
            $categories = $category::all();
            return response()->json([
                'status' => 200,
                'message' => 'Categories retrieved successfully',
                'data' => $categories,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while retrieving categories',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            // Get the validated data from the request
            $data = $request->validated();

            // Create a new category using the validated data
            $category = Category::create($data);

            return response()->json([
                'status' => 201,
                'message' => 'Category created successfully',
                'data' => $category,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Server Error',
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Category $category, string $id)
    {
        // show
        return response()->json([
            'status' => 200,
            'message' => 'City retrieved successfully.',
            'data' => $category,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, string $id)
    {
        try {
            // find the category by id
            $category = Category::findOrFail($id);
            // get the validated data from the request
            $data = $request->validated();
            // update the category with the validated data
            $category->update($data);
            // return a success response
            return response()->json([
                'status' => 200,
                'message' => 'Category updated successfully',
                'data' => $category,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
