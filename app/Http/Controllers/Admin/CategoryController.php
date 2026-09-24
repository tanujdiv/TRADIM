<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('videos')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view(
            'admin.categories.create'
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'unique:categories,name',
            ],

            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:categories,slug',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    public function edit(
        Category $category
    ): View {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    public function update(
        Request $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                Rule::unique(
                    'categories',
                    'name'
                )->ignore($category->id),
            ],

            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique(
                    'categories',
                    'slug'
                )->ignore($category->id),
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    public function destroy(
        Category $category
    ): RedirectResponse {
        if ($category->videos()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because videos are using it.'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'Category deleted successfully.'
        );
    }
}