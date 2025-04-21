<?php

namespace App\Http\Controllers;

use App\Models\BookCollection;
use App\Http\Requests\BookCollectionRequest;
use Illuminate\Http\Request;

class BookCollectionController extends Controller
{
    public function index()
    {
        $collections = BookCollection::all();
        return view('vendor.argon.bookcollection.index', compact('collections'));
    }

    public function create()
    {
        return view('vendor.argon.bookcollection.create');
    }

    public function store(BookCollectionRequest $request)
    {
        $collection = BookCollection::create($request->validated());
        return redirect()->route('bookcollection.index')->with('success', 'Collection created!');
    }

    public function show(BookCollection $bookcollection)
    {
        return view('vendor.argon.bookcollection.show', compact('bookcollection'));
    }

    public function edit(BookCollection $bookcollection)
    {
        return view('vendor.argon.bookcollection.edit', compact('bookcollection'));
    }

    public function update(BookCollectionRequest $request, BookCollection $bookcollection)
    {
        $bookcollection->update($request->validated());
        return redirect()->route('bookcollection.index')->with('success', 'Collection updated!');
    }

    public function destroy(BookCollection $bookcollection)
    {
        $bookcollection->delete();
        return redirect()->route('bookcollection.index')->with('success', 'Collection deleted!');
    }
}
