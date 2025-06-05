<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Argon Dashboard-aligned routes

// Root route: show login for guests, dashboard for authenticated
Route::get('/', function () {
    // If authenticated, redirect to dashboard. If not, show login view.
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
});

// Dashboard and all features require authentication
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $role = auth()->user()->role ?? null;
        if ($role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'Member') {
            return redirect()->route('member.dashboard');
        }
        // Instead of logging out and redirecting to /login, just show a generic error view
        // to avoid redirect loops if role is missing or invalid
        return response('Your account does not have a valid role. Please contact support.', 403);
    })->name('dashboard');
    // Alias /home to dashboard for legacy compatibility
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('role:Admin');
        
    // Admin Reports
    Route::get('/admin/reports/{period?}', [AdminDashboardController::class, 'activityReport'])
        ->name('admin.reports')
        ->middleware('role:Admin');

    // Admin test route for debugging 403
    Route::get('/admin/test', function () {
        return 'ADMIN TEST';
    })->middleware(['auth', 'role:Admin']);

    // Member Dashboard
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])
        ->name('member.dashboard')
        ->middleware('role:Member');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    // Allow both POST and PUT/PATCH for profile update compatibility
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile', [ProfileController::class, 'update']);

    // Borrow Workflow (custom routes FIRST to avoid resource shadowing)
    Route::post('/borrow/{loan_id}/approve', [BorrowController::class, 'approve'])->middleware('role:Admin')->name('borrow.approve');
    Route::post('/borrow/{loan_id}/reject', [BorrowController::class, 'reject'])->middleware('role:Admin')->name('borrow.reject');
    Route::post('/borrow/{loan_id}/return', [BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::post('/borrow/request/{book}', [BorrowController::class, 'request'])->name('borrow.request');
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::get('/borrow/create', [BorrowController::class, 'create'])->name('borrow.create');
    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');
    Route::get('/borrow/{loan_id}', [BorrowController::class, 'show'])->name('borrow.show');
    Route::get('/borrow/{loan_id}/edit', [BorrowController::class, 'edit'])->name('borrow.edit');
    Route::put('/borrow/{loan_id}', [BorrowController::class, 'update'])->name('borrow.update');
    Route::delete('/borrow/{loan_id}', [BorrowController::class, 'destroy'])->name('borrow.destroy');

    // Admin-only resourceful routes
    Route::middleware('role:Admin')->group(function () {
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('members', App\Http\Controllers\MemberController::class);
        Route::resource('announcements', App\Http\Controllers\AnnouncementController::class)->except(['show']);
    });
    
    // Book routes with role separation
    Route::resource('books', App\Http\Controllers\BookController::class)->only(['show']); // Common routes
    
    // Admin Book routes
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/books', [App\Http\Controllers\BookController::class, 'adminIndex'])->name('admin.books.index');
        Route::resource('books', App\Http\Controllers\BookController::class)->only(['store', 'update', 'destroy', 'create', 'edit']);
    });
    
    // Member Book routes
    Route::middleware('role:Member')->group(function () {
        Route::get('/member/books', [App\Http\Controllers\BookController::class, 'memberIndex'])->name('member.books.index');
    });
    
    // Member-specific resourceful routes
    Route::middleware('role:Member')->group(function () {
        // Define explicit routes with names for Member resources
        Route::get('/member/wishlists', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlists.index');
        Route::post('/member/wishlists', [App\Http\Controllers\WishlistController::class, 'store'])->name('wishlists.store');
        Route::delete('/member/wishlists/{wishlist}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlists.destroy');
        Route::post('/member/wishlists/{wishlist}/borrow', [App\Http\Controllers\WishlistController::class, 'borrowFromWishlist'])->name('wishlists.borrow');
        
        Route::get('/member/bookmarks', [App\Http\Controllers\BookmarkController::class, 'index'])->name('bookmarks.index');
        Route::post('/member/bookmarks', [App\Http\Controllers\BookmarkController::class, 'store'])->name('bookmarks.store');
        Route::delete('/member/bookmarks/{bookmark}', [App\Http\Controllers\BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
        
        Route::get('/member/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/member/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/member/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/member/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
    
    // Admin can view all reviews, wishlists, bookmarks
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
        Route::get('/admin/reviews/{review}', [ReviewController::class, 'show'])->name('admin.reviews.show');
        Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
        Route::get('/admin/wishlists', [App\Http\Controllers\WishlistController::class, 'adminIndex'])->name('admin.wishlists.index');
        Route::get('/admin/bookmarks', [App\Http\Controllers\BookmarkController::class, 'adminIndex'])->name('admin.bookmarks.index');
    });
    
    // Public routes for all authenticated users
    Route::get('/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');

    // Add review for book
    Route::post('/books/{book}/add-review', [App\Http\Controllers\BookController::class, 'addReview'])->name('books.addReview');

    // Bookmarks
    Route::post('/books/{book}/bookmark', [App\Http\Controllers\BookmarkController::class, 'store'])->name('books.bookmark');
    // Wishlist
    Route::post('/books/{book}/wishlist', [App\Http\Controllers\WishlistController::class, 'store'])->name('books.wishlist');
    // Borrow Request
    Route::post('/books/{book}/borrow-request', [App\Http\Controllers\BorrowController::class, 'store'])->name('borrow.request');

    // Add fallback route for Argon sidebar demo links
    Route::get('/{page}', function ($page) {
        // Only allow certain demo pages to avoid security risk
        $allowedPages = ['user-management', 'tables', 'billing'];
        if (in_array($page, $allowedPages)) {
            return view('vendor.argon.pages.' . $page);
        }
        abort(404);
    })->where('page', 'user-management|tables|billing')->name('page');
});

// Auth Controllers (only for guests)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        // If already logged in, redirect to dashboard
        if (auth()->check()) {
            $role = auth()->user()->role ?? null;
            if ($role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'Member') {
                return redirect()->route('member.dashboard');
            }
            Auth::logout();
            return redirect('/login')->withErrors(['role' => 'Your account does not have a valid role. Please contact support.']);
        }
        return view('auth.login');
    })->name('login');
    Route::get('/register', function () {
        // If already logged in, redirect to dashboard
        if (auth()->check()) {
            $role = auth()->user()->role ?? null;
            if ($role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'Member') {
                return redirect()->route('member.dashboard');
            }
            Auth::logout();
            return redirect('/login')->withErrors(['role' => 'Your account does not have a valid role. Please contact support.']);
        }
        return view('auth.register');
    })->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register.perform');
});

// Auth Controllers
Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])->name('login.perform');
Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
// Remove email verification routes as forget-password and email verification features are disabled
// Route::get('/verify-email', [App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
// Route::get('/verify-email/{id}/{hash}', [App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])->name('verification.verify');
// Route::post('/email/verification-notification', [App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])->name('verification.send');
Route::get('/confirm-password', [App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
Route::post('/confirm-password', [App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);
// Route::put('/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

// Remove reset password and related routes
// (All password reset routes and aliases have been deleted)

// Fallback route: redirect all non-existing routes to dashboard
Route::fallback(function () {
    if (auth()->check()) {
        $role = auth()->user()->role ?? null;
        if ($role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'Member') {
            return redirect()->route('member.dashboard');
        }
        Auth::logout();
        return redirect('/login')->withErrors(['role' => 'Your account does not have a valid role. Please contact support.']);
    }
    return redirect()->route('login');
});
