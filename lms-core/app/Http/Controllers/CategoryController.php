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
     * Display the specified category with its books and statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Kategori  $category
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Kategori $category)
    {
        // Query builder for books in this category
        $query = \App\Models\Buku::where('category_id', $category->category_id);
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        
        // Apply availability filter if provided
        if ($request->has('availability') && !empty($request->availability)) {
            if ($request->availability == 'available') {
                $query->where('available_qty', '>', 0);
            } else if ($request->availability == 'unavailable') {
                $query->where('available_qty', 0);
            }
        }
        
        // Paginate the results and preserve query parameters
        $books = $query->orderBy('title')->paginate(12)->appends($request->all());
        
        // Category statistics
        $totalBooks = $category->bukus()->count();
        $totalAvailableQty = $category->bukus()->sum('available_qty');
        $totalBorrowedQty = $category->bukus()->sum('borrowed_qty');
        
        // Calculate availability ratio (available vs borrowed)
        $totalQty = $totalAvailableQty + $totalBorrowedQty;
        $availabilityRatio = ($totalQty > 0) ? round(($totalAvailableQty / $totalQty) * 100) : 100;
        
        // Most popular books in this category (most borrowed)
        $popularBooks = \App\Models\Log_Pinjam_Buku::select('book_id', \DB::raw('COUNT(*) as borrow_count'))
            ->whereHas('buku', function($query) use ($category) {
                $query->where('category_id', $category->category_id);
            })
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $book = \App\Models\Buku::find($item->book_id);
                if ($book) {
                    $book->borrow_count = $item->borrow_count;
                }
                return $book;
            })
            ->filter();
            
        // Recent additions to this category
        $recentBooks = $category->bukus()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        
        return view('vendor.argon.categories.show', compact(
            'category', 
            'books', 
            'totalBooks', 
            'totalAvailableQty', 
            'totalBorrowedQty',
            'availabilityRatio',
            'popularBooks',
            'recentBooks'
        ));
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
