<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

/**
 * Class BookController
 *
 * @package App\Http\Controllers
 */
class BookController extends Controller
{
    /**
     * Display a listing of the books.
     *
     * @param  Request  $request
     * @return View|Factory
     */
    public function index(Request $request): View|Factory
    {
        $query = Buku::query()->with('category');
        if ($request->filled('search')) {
            $search = $request->input('search', '');
            assert(is_string($search));
            $query->where(function ($q) use ($search) {
                assert($q instanceof \Illuminate\Database\Eloquent\Builder);
                $q->where('title', 'like', "%$search%")
                    ->orWhere('author', 'like', "%$search%")
                    ->orWhere('isbn', 'like', "%$search%")
                    ->orWhereHas('category', function ($qc) use ($search) {
                        assert($qc instanceof \Illuminate\Database\Eloquent\Builder);
                        $qc->where('category_name', 'like', "%$search%");
                    });
            });
        }
        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            assert(is_numeric($categoryId) || is_string($categoryId));
            $query->where('category_id', $categoryId);
        }
        $books = $query->orderBy('title')->paginate(10)->appends($request->all());
        $categories = Kategori::query()->orderBy('category_name')->get();

        return view('vendor.argon.books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new book.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        $categories = Kategori::query()->orderBy('category_name')->get();
        return view('vendor.argon.books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  BookRequest  $request
     * @return RedirectResponse
     */
    public function store(BookRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        assert(is_array($attributes));
        /** @var array<string, mixed> $attributes */
        Buku::create($attributes);
        return Redirect::route('books.index')->with('status', 'Book created.');
    }

    /**
     * Display the specified book.
     *
     * @param  Buku  $book
     * @return View|Factory
     */
    public function show(Buku $book): View|Factory
    {
        $book->load('category');
        return view('vendor.argon.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     *
     * @param  Buku  $book
     * @return View|Factory
     */
    public function edit(Buku $book): View|Factory
    {
        $categories = Kategori::query()->orderBy('category_name')->get();
        return view('vendor.argon.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book in storage.
     *
     * @param  BookRequest  $request
     * @param  Buku  $book
     * @return RedirectResponse
     */
    public function update(BookRequest $request, Buku $book): RedirectResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['title', 'author', 'isbn', 'category_id', 'publication_year', 'publisher', 'total_stock']);
        $book->update($attributes);
        return Redirect::route('books.index')->with('status', 'Book updated.');
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  Buku  $book
     * @return RedirectResponse
     */
    public function destroy(Buku $book): RedirectResponse
    {
        $book->delete();
        return Redirect::route('books.index')->with('status', 'Book deleted.');
    }
}
