<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Kategori;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = \App\Models\Kategori::query()->orderBy('category_name')->paginate(10);
        return view('vendor.argon.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vendor.argon.categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\App\Http\Requests\CategoryRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        /** @var array{category_name: string} $validated */
        $category = new \App\Models\Kategori();
        $category->category_name = $validated['category_name'];
        $category->save();
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('categories.index')->with('success', 'Category created successfully.');
        return $resp;
    }

    /**
     * Display the specified category.
     *
     * @param  Kategori  $category
     * @return \Illuminate\View\View
     */
    public function show(Kategori $category)
    {
        return view('vendor.argon.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  Kategori  $category
     * @return \Illuminate\View\View
     */
    public function edit(Kategori $category)
    {
        return view('vendor.argon.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(\App\Http\Requests\CategoryRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        /** @var array{category_name: string} $validated */
        $category = \App\Models\Kategori::findOrFail($id);
        $category->category_name = $validated['category_name'];
        $category->save();
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        return $resp;
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  Kategori  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Kategori $category)
    {
        $category->delete();
        return redirect('/categories')->with('status', 'Category deleted.');
    }
}
