<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Music',
                'description' => 'Music videos and songs',
                'icon' => 'bi-music-note-beamed',
            ],

            [
                'name' => 'Gaming',
                'description' => 'Gaming videos, streams and gameplay',
                'icon' => 'bi-controller',
            ],

            [
                'name' => 'Education',
                'description' => 'Learn new skills and knowledge',
                'icon' => 'bi-mortarboard',
            ],

            [
                'name' => 'Technology',
                'description' => 'Technology, software and gadgets',
                'icon' => 'bi-cpu',
            ],

            [
                'name' => 'Entertainment',
                'description' => 'Entertainment and fun content',
                'icon' => 'bi-film',
            ],

            [
                'name' => 'Sports',
                'description' => 'Sports and fitness content',
                'icon' => 'bi-trophy',
            ],

            [
                'name' => 'News',
                'description' => 'Latest news and current events',
                'icon' => 'bi-newspaper',
            ],

            [
                'name' => 'Travel',
                'description' => 'Travel, destinations and experiences',
                'icon' => 'bi-airplane',
            ],

            [
                'name' => 'Lifestyle',
                'description' => 'Lifestyle, daily life and experiences',
                'icon' => 'bi-heart',
            ],

            [
                'name' => 'Comedy',
                'description' => 'Comedy and funny videos',
                'icon' => 'bi-emoji-laughing',
            ],

            [
                'name' => 'News & Politics',
                'description' => 'News and political discussions',
                'icon' => 'bi-globe',
            ],

            [
                'name' => 'Science',
                'description' => 'Science and discoveries',
                'icon' => 'bi-rocket',
            ],

        ];


        foreach ($categories as $index => $category) {

            Category::updateOrCreate(

                [
                    'slug' => Str::slug(
                        $category['name']
                    ),
                ],

                [

                    'name' => $category['name'],

                    'description' =>
                        $category['description'],

                    'icon' =>
                        $category['icon'],

                    'is_active' => true,

                    'sort_order' => $index + 1,

                ]

            );
        }
    }
}