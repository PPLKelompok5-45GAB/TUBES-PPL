<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Review_Buku;
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
     * Display a listing of the books. This method now serves as a redirector
     * based on user role.
     *
     * @param  Request  $request
     * @return View|Factory|RedirectResponse
     */
    public function index(Request $request)
    {
        // Redirect based on user role
        if (auth()->user()->role === 'Admin') {
            return redirect()->route('admin.books.index', $request->query());
        } elseif (auth()->user()->role === 'Member') {
            return redirect()->route('member.books.index', $request->query());
        }
        
        // Fallback for any undefined roles (should not happen with proper middleware)
        return redirect()->route('dashboard');
    }
    
    /**
     * Display a listing of books for administrators with advanced filtering and statistics.
     *
     * @param  Request  $request
     * @return View|Factory
     */
    public function adminIndex(Request $request): View|Factory
    {
        // Start with base query and eager load relationships to reduce queries
        $query = Buku::query()->with(['category']);
        
        // Apply search filters if provided
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
        
        // Apply category filter if provided
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            assert(is_numeric($categoryId) || is_string($categoryId));
            $query->where('category_id', $categoryId);
        }
        
        // Apply availability filter if provided
        if ($request->filled('availability')) {
            if ($request->input('availability') === 'available') {
                $query->where('available_qty', '>', 0);
            } elseif ($request->input('availability') === 'unavailable') {
                $query->where('available_qty', 0);
            }
        }
        
        // Get paginated results with efficient ordering
        $books = $query->orderBy('title')->paginate(10)->appends($request->all());
        
        // Get categories for filter dropdown
        $categories = Kategori::orderBy('category_name')->get();
        
        // Calculate statistics efficiently (without loading all records)
        $totalBooks = Buku::count();
        $availableBooks = Buku::sum('available_qty'); // Changed to sum to get actual quantity
        $borrowedBooks = Buku::sum('borrowed_qty');
        $totalCategories = Kategori::count();
        
        return view('vendor.argon.books.admin-index', [
            'books' => $books,
            'categories' => $categories,
            'totalBooks' => $totalBooks,
            'availableBooks' => $availableBooks,
            'borrowedBooks' => $borrowedBooks,
            'totalCategories' => $totalCategories,
        ]);
    }
    
    /**
     * Display a listing of books for members with simplified gallery view.
     *
     * @param  Request  $request
     * @return View|Factory
     */
    public function memberIndex(Request $request): View|Factory
    {
        // Start with base query and eager load relationships to reduce queries
        $query = Buku::query()->with(['category']);
        
        // Apply search filters if provided
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
        
        // Apply category filter if provided
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            assert(is_numeric($categoryId) || is_string($categoryId));
            $query->where('category_id', $categoryId);
        }
        
        // Get paginated results with efficient ordering - more items per page for gallery view
        $books = $query->orderBy('title')->paginate(12)->appends($request->all());
        
        // Get categories for filter dropdown
        $categories = Kategori::orderBy('category_name')->get();
        
        return view('vendor.argon.books.member-index', [
            'books' => $books,
            'categories' => $categories,
        ]);
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
        $attributes = $request->only(['title', 'author', 'isbn', 'category_id', 'publication_year', 'publisher', 'synopsis']);
        
        // Set total_stock from the form input
        $attributes['total_stock'] = $request->input('total_stock');
        
        // Initialize borrowed_qty to 0 for new books
        $attributes['borrowed_qty'] = 0;
        
        // Calculate available_qty as total_stock - borrowed_qty
        $attributes['available_qty'] = $attributes['total_stock'];
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $attributes['image'] = $imagePath;
        }
        
        Buku::create($attributes);
        return Redirect::route('admin.books.index')->with('status', 'Book created.');
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
        $categories = \App\Models\Kategori::all();
        
        $existingBookmark = null;
        if (auth()->check() && auth()->user()->role === 'Member') {
            $member = auth()->user()->member;
            if ($member) {
                $existingBookmark = \App\Models\Bookmark::where('book_id', $book->book_id)
                    ->where('member_id', $member->member_id)
                    ->first();
            }
        }
        
        return view('vendor.argon.books.show', compact('book', 'categories', 'existingBookmark'));
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
        $attributes = $request->only(['title', 'author', 'isbn', 'category_id', 'publication_year', 'publisher', 'synopsis']);
        
        // Get the new total_stock value from the form
        $newTotalStock = $request->input('total_stock');
        
        // Keep the current borrowed_qty
        $currentBorrowedQty = $book->borrowed_qty;
        
        // Update total_stock
        $attributes['total_stock'] = $newTotalStock;
        
        // Keep the current borrowed_qty
        $attributes['borrowed_qty'] = $currentBorrowedQty;
        
        // Calculate the new available_qty based on the new total_stock and current borrowed_qty
        $attributes['available_qty'] = max(0, $newTotalStock - $currentBorrowedQty);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $attributes['image'] = $imagePath;
        }
        
        $book->update($attributes);
        return Redirect::route('admin.books.index')->with('status', 'Book updated.');
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
        return Redirect::route('admin.books.index')->with('status', 'Book deleted.');
    }

    /**
     * Store a new review for a book (using Review_Buku model directly).
     */
    public function addReview(Request $request, $bookId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:255',
        ]);

        $book = Buku::findOrFail($bookId);
        $memberId = auth()->user()->member->member_id ?? null;
        if (!$memberId) {
            return redirect()->back()->with('error', 'You must be a member to add a review.');
        }

        Review_Buku::create([
            'book_id' => $book->book_id,
            'member_id' => $memberId,
            'rating' => $request->input('rating'),
            'review_text' => $request->input('review_text'),
            'review_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Review added successfully!');
    }
}
