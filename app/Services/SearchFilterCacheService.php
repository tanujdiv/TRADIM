<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SearchFilterCacheService
{
    private const CATEGORIES_KEY = 'tradim:search:active-categories';

    private const CACHE_SECONDS = 300;

    public function categories(): Collection
    {
        return Cache::remember(
            self::CATEGORIES_KEY,
            self::CACHE_SECONDS,
            function (): Collection {
                return Category::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'slug',
                    ]);
            }
        );
    }

    public function clear(): void
    {
        Cache::forget(self::CATEGORIES_KEY);
    }
}