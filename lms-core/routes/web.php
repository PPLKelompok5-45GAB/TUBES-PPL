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
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Borrow Workflow (custom routes FIRST to avoid resource shadowing)
    Route::post('/borrow/{loan_id}/approve', [BorrowController::class, 'approve'])->middleware('role:Admin')->name('borrow.approve');
    Route::post('/borrow/{loan_id}/reject', [BorrowController::class, 'reject'])->middleware('role:Admin')->name('borrow.reject');
    Route::post('/borrow/{loan_id}/return', [BorrowController::class, 'returnBook'])->middleware('role:Member')->name('borrow.return');
    Route::get('/borrow/{loan_id}', [BorrowController::class, 'show'])->name('borrow.show');
    Route::get('/borrow/{loan_id}/edit', [BorrowController::class, 'edit'])->name('borrow.edit');
    Route::put('/borrow/{loan_id}', [BorrowController::class, 'update'])->name('borrow.update');
    Route::delete('/borrow/{loan_id}', [BorrowController::class, 'destroy'])->name('borrow.destroy');
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::get('/borrow/create', [BorrowController::class, 'create'])->name('borrow.create');
    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');

    // Resourceful routes for core LMS features (Argon-aligned)
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('books', App\Http\Controllers\BookController::class);
    Route::resource('members', App\Http\Controllers\MemberController::class);
    Route::resource('borrow', App\Http\Controllers\BorrowController::class);
    Route::resource('announcements', App\Http\Controllers\AnnouncementController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('wishlists', App\Http\Controllers\WishlistController::class)->only(['index', 'store', 'destroy']);
    Route::resource('bookmarks', App\Http\Controllers\BookmarkController::class);
    Route::resource('bookcollection', App\Http\Controllers\BookCollectionController::class);
    Route::get('reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

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
        return view('auth.login');
    })->name('login');
    Route::get('/register', function () {
        if (auth()->check()) {
            $role = auth()->user()->role ?? null;
            if ($role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'Member') {
                return redirect()->route('member.dashboard');
            }
            // fallback: logout and redirect to login
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
