<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\View\View;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::count(),

            'active_users' => User::where(
                'is_active',
                true
            )->count(),

            'channels' => Channel::count(),

            'videos' => Video::count(),

            'published_videos' => Video::where(
                'status',
                'published'
            )->count(),

            'processing_videos' => Video::where(
                'status',
                'processing'
            )->count(),

            'categories' => Category::count(),

            'comments' => Comment::count(),

            'pending_reports' => Report::where(
                'status',
                'pending'
            )->count(),
        ];

        $latestUsers = User::query()
            ->latest()
            ->take(8)
            ->get();

        $latestVideos = Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->latest()
            ->take(8)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'stats',
                'latestUsers',
                'latestVideos'
            )
        );
    }
}