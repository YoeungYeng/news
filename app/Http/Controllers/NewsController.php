<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(News $news)
    {
        try {
            // index method to retrieve all news articles
            $newsArticles = $news::orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => 200,
                'message' => 'News articles retrieved successfully',
                'data' => $newsArticles,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while retrieving news articles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        try {
            // get the validated data from the request
            $data = $request->validated();
            // upload image
            if ($request->hasFile('image')) {
                $data['image'] = $request->uploadImage($request->file('image'));
            }
            // create a new news article using the validated data
            $newsArticle = News::create($data);
            return response()->json([
                'status' => 201,
                'message' => 'News article created successfully',
                'data' => $newsArticle,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the news article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news, string $id)
    {
        try {
            // find the news article by id
            $newsArticle = $news::findOrFail($id);
            return response()->json([
                'status' => 200,
                'message' => 'News article retrieved successfully',
                'data' => $newsArticle,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'News article not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, string $id)
    {
        try {
            // find the news article by id
            $newsArticle = News::findOrFail($id);
            // get the validated data from the request
            $data = $request->validated();
            // upload image if provided
            if ($request->hasFile('image')) {
                $data['image'] = $request->uploadImage($request->file('image'));
            }
            // update the news article with the validated data
            $newsArticle->update($data);
            return response()->json([
                'status' => 200,
                'message' => 'News article updated successfully',
                'data' => $newsArticle,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the news article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news, string $id)
    {
        try {
            // find the news article by id
            $newsArticle = $news::findOrFail($id);
            // delete the news article
            $newsArticle->delete();
            return response()->json([
                'status' => 200,
                'message' => 'News article deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the news article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // join table category and news
    public function newsWithCategory(News $news)
    {
        try {
            // retrieve news articles with their associated categories      
            $newsArticles = $news::with('category')->get();
            return response()->json([
                'status' => 200,
                'message' => 'News articles with categories retrieved successfully',
                'data' => $newsArticles,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while retrieving news articles with categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // join table category and news by category id
    public function newsByCategory(string $id): JsonResponse
    {
        try {
            // Retrieve all news articles that belong to a single category ID
            $newsArticles = News::with('category')
                ->where('category_id', $id)
                ->latest()
                ->get();

            if ($newsArticles->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No news articles found for this category.',
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'message' => 'News articles by category retrieved successfully.',
                'data' => $newsArticles,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while retrieving news articles by category.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Server Error',
            ], 500);
        }
    }
}
