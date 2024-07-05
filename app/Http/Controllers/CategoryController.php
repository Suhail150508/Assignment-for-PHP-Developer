<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $startTime = microtime(true);
        $cacheKey = 'categories_all';

        try {
            $categories = Redis::get($cacheKey);

            if (!$categories) {
                $categories = Category::with('parent')->get();
                Redis::set($cacheKey, $categories->toJson());
                Redis::expire($cacheKey, 60 * 60); // Cache for 1 hour
            } else {
                $categories = collect(json_decode($categories));
            }
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Redis error: ' . $e->getMessage());
        }

            $endTime = microtime(true);
            $renderTime = $endTime - $startTime;

            return view('Category.categories', compact('categories', 'renderTime'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name',
                'parent_id' => 'nullable|exists:categories,id'
            ]);

            Category::create($request->all());
            Redis::del('categories_all');
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id,
                'parent_id' => 'nullable|exists:categories,id'
            ]);

            $category->update($request->all());
            Redis::del('categories_all');
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            Redis::del('categories_all');
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    public function deactivate(Category $category)
    {
        try {
            $category->deactivate();
            Redis::del('categories_all');
            return redirect()->route('categories.index')->with('success', 'Category deactivated successfully.');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to deactivate category: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $startTime = microtime(true);

        $categoryName = $request->input('name');
        $cacheKey = 'search_category_' . $categoryName;

        try {
            $cachedCategory = Redis::get($cacheKey);

            if ($cachedCategory) {
                $categories = collect(json_decode($cachedCategory));
            } else {
                $category = Category::where('name', $categoryName)->first();

                if ($category) {
                    $categories = Category::getNestedCategories($category->id);
                    Redis::set($cacheKey, $categories->toJson());
                    Redis::expire($cacheKey, 60 * 60); // Cache for 1 hour
                } else {
                    return response()->json(['message' => 'Category not found'], 404);
                }
            }
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Redis error: ' . $e->getMessage());
        }

            $endTime = microtime(true);
            $renderTime = $endTime - $startTime;

            return view('Category.categories', compact('categories', 'renderTime'));
    }
}
