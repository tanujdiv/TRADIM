<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VideoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Video List
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): JsonResponse {

        $perPage = max(
            1,
            min(
                $request->integer('per_page', 12),
                50
            )
        );

        $videos = Video::query()
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
            ->paginate($perPage);


        return response()->json([

            'success' => true,

            'data' => $videos,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Show Video
    |--------------------------------------------------------------------------
    */

    public function show(
        Video $video
    ): JsonResponse {

        if (
            $video->status !== 'published'
            || $video->visibility !== 'public'
            || !$video->published_at
        ) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Video not found.',

            ], 404);
        }


       
        Gate::authorize('view', $video);


        $video->load([
            'channel',
            'category',
        ]);


        $relatedVideos = Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->where(
                'id',
                '!=',
                $video->id
            )
            ->where(
                'status',
                'published'
            )
            ->where(
                'visibility',
                'public'
            )
            ->when(
                $video->category_id,
                function ($query) use ($video) {

                    $query->where(
                        'category_id',
                        $video->category_id
                    );

                }
            )
            ->latest('published_at')
            ->take(12)
            ->get();


        return response()->json([

            'success' => true,

            'data' => [

                'video' =>
                    $video,

                'related_videos' =>
                    $relatedVideos,

            ],

        ]);
    }
}