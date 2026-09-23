<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where(
                'is_active',
                true
            )
            ->orderBy(
                'sort_order'
            )
            ->orderBy(
                'name'
            )
            ->get();


        return response()->json([

            'success' => true,

            'data' => $categories,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Category Details
    |--------------------------------------------------------------------------
    */

    public function show(
        Category $category
    ): JsonResponse {

        if (!$category->is_active) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Category not found.',

            ], 404);
        }


        $videos = $category
            ->videos()
            ->with([
                'channel',
                'category',
            ])
            ->where(
                'status',
                'published'
            )
            ->where(
                'visibility',
                'public'
            )
            ->whereNotNull(
                'published_at'
            )
            ->latest('published_at')
            ->paginate(12);


        return response()->json([

            'success' => true,

            'data' => [

                'category' =>
                    $category,

                'videos' =>
                    $videos,

            ],

        ]);
    }
}