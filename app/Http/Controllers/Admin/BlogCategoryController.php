<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategory\StoreRequest;
use App\Http\Requests\BlogCategory\UpdateRequest;
use App\Models\BlogCategory;
use Exception;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::latest()->paginate();
        return inertia('admin/blog-categories/Index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('admin/blog-categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $slug = Str::slug($request->name);
            $input = $request->validated();
            $input['slug'] = $slug;
            $input['created_by'] = request()->user()->id;

            BlogCategory::create($input);

            return redirect()->route('admin.blog-categories.index')
                ->with('success', 'Blog category created successfully!');
        } catch (Exception $e) {
            return redirect()->route('admin.blog-categories.index')
                ->with('error', 'Failed to create blog category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $slug = Str::slug($request->name);
            $input = $request->validated();
            $input['slug'] = $slug;

            BlogCategory::where('id', $id)->update($input);

            return redirect()->route('admin.blog-categories.index')
                ->with('success', 'Blog category updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('admin.blog-categories.index')
                ->with('error', 'Failed to update blog category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BlogCategory::destroy($id);
        return redirect()->route('admin.blog-categories.index')->with('success', 'Category deleted successfully.');
    }
}
