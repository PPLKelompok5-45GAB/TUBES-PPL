<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 shadow-lg custom-sidenav-gradient" id="sidenav-main" data-color="primary" style="min-height: 90vh; width: 250px; left: 0; top: 0; bottom: 0; transition: left 0.3s cubic-bezier(.4,2,.6,1);">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('dashboard') }}" target="_blank">
            <i class="fas fa-book-reader fa-2x mb-2 text-primary"></i>
            <span class="ms-2 font-weight-bold" style="font-size:1.45rem; letter-spacing: 1px;">LibraLink</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mb-2">
                @if(auth()->user()->role === 'Admin')
                    <a class="nav-link d-flex align-items-center custom-nav-link {{ request()->is('admin/dashboard') || Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10 me-2"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                @elseif(auth()->user()->role === 'Member')
                    <a class="nav-link d-flex align-items-center custom-nav-link {{ request()->is('member/dashboard') || Route::currentRouteName() == 'member.dashboard' ? 'active' : '' }}" href="{{ route('member.dashboard') }}">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10 me-2"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                @endif
            </li>
            <!-- LMS Core Navigation -->
            @if(auth()->user()->role === 'Admin')
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}"><i class="fas fa-tags me-2"></i><span class="nav-link-text">Categories</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('admin/books*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}"><i class="fas fa-book me-2"></i><span class="nav-link-text">Books</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('members*') ? 'active' : '' }}" href="{{ route('members.index') }}"><i class="fas fa-users me-2"></i><span class="nav-link-text">Members</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('borrow*') ? 'active' : '' }}" href="{{ route('borrow.index') }}"><i class="fas fa-book-reader me-2"></i><span class="nav-link-text">Borrow</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('announcements*') ? 'active' : '' }}" href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn me-2"></i><span class="nav-link-text">Announcements</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}"><i class="fas fa-star me-2"></i><span class="nav-link-text">Reviews</span></a></li>
            @elseif(auth()->user()->role === 'Member')
            <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('member/books*') ? 'active' : '' }}" href="{{ route('member.books.index') }}"><i class="fas fa-book me-2"></i><span class="nav-link-text">Books</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('borrow*') ? 'active' : '' }}" href="{{ route('borrow.index') }}"><i class="fas fa-book-reader me-2"></i><span class="nav-link-text">Borrow</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('announcements*') ? 'active' : '' }}" href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn me-2"></i><span class="nav-link-text">Announcements</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('member/bookmarks*') ? 'active' : '' }}" href="{{ route('bookmarks.index') }}"><i class="fas fa-bookmark me-2"></i><span class="nav-link-text">Bookmarks</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('member/wishlists*') ? 'active' : '' }}" href="{{ route('wishlists.index') }}"><i class="fas fa-heart me-2"></i><span class="nav-link-text">Wishlists</span></a></li>
                <li class="nav-item mb-2"><a class="nav-link custom-nav-link {{ request()->is('member/reviews*') ? 'active' : '' }}" href="{{ route('reviews.index') }}"><i class="fas fa-star me-2"></i><span class="nav-link-text">Reviews</span></a></li>
            @endif
            <!-- End LMS Core Navigation -->
        </ul>
    </div>
    <!-- Sidebar Bottom Section -->
    @php use Illuminate\Support\Str; @endphp
    <div class="sidenav-footer mt-auto px-3 pb-4">
        <div class="custom-userbox d-flex align-items-center p-2 mb-2">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&rounded=true&size=40" class="rounded-circle me-2" width="40" height="40" alt="User Avatar">
            <div>
                <div class="fw-semibold" style="font-size: 1rem;">{{ auth()->user()->name }}</div>
                <div class="text-muted small">{{ auth()->user()->username ?? Str::slug(auth()->user()->name, '') }}</div>
            </div>
        </div>
        <div class="d-flex gap-2 mb-2">
            <a href="{{ route('profile') }}" class="btn btn-sm btn-outline-primary flex-fill"><i class="fas fa-cog"></i> Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger flex-fill"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
        <div class="text-center mt-3 small text-secondary opacity-75">
            LibraLink v1.0 &copy; {{ date('Y') }}
        </div>
    </div>
</aside>
<button id="sidenav-open-btn" class="btn btn-primary position-fixed" style="bottom:24px;left:24px;z-index:1100;display:none;"><i class="fas fa-bars"></i></button>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidenav = document.getElementById('sidenav-main');
        var iconSidenav = document.getElementById('iconSidenav');
        var openBtn = document.getElementById('sidenav-open-btn');
        // Hide sidebar
        if (iconSidenav && sidenav && openBtn) {
            iconSidenav.addEventListener('click', function() {
                if (!sidenav.classList.contains('d-none')) {
                    sidenav.classList.add('d-none');
                    openBtn.style.display = 'block';
                    document.body.classList.remove('sidebar-open');
                }
            });
            openBtn.addEventListener('click', function() {
                sidenav.classList.remove('d-none');
                openBtn.style.display = 'none';
                document.body.classList.add('sidebar-open');
            });
        }
        // On page load, ensure sidebar-open is set if sidebar is visible
        if (!sidenav.classList.contains('d-none')) {
            document.body.classList.add('sidebar-open');
        } else {
            document.body.classList.remove('sidebar-open');
        }
    });
</script>
<style>
    .main-content {
        transition: margin-left 0.3s cubic-bezier(.4,2,.6,1), padding-left 0.3s cubic-bezier(.4,2,.6,1);
        margin-left: 0;
        padding-left: 0;
    }
    body.sidebar-open .main-content {
        margin-left: 250px;
        padding-left: 1.5rem;
    }
    @media (max-width: 991.98px) {
        body.sidebar-open .main-content {
            margin-left: 0;
            padding-left: 0.5rem;
        }
        #sidenav-main {
            z-index: 1200;
        }
    }
    .custom-sidenav-gradient {
        background: linear-gradient(135deg, #f8fafc 0%, #e3e9f7 100%) !important;
        border-radius: 1.5rem;
        box-shadow: 0 6px 24px rgba(80, 112, 168, 0.13), 0 1.5px 6px rgba(80, 112, 168, 0.08);
    }
    .custom-nav-link {
        border-radius: 0.75rem;
        padding: 0.7rem 1.2rem;
        font-size: 1.06rem;
        transition: all 0.17s cubic-bezier(.4,2,.6,1);
        color: #374151;
        margin-left: 0.2rem;
        margin-right: 0.2rem;
        font-weight: 500;
    }
    .custom-nav-link .nav-link-text {
        font-weight: 500;
        letter-spacing: 0.01em;
    }
    .custom-nav-link:hover, .custom-nav-link.active {
        background: linear-gradient(90deg, #dbeafe 0%, #a5b4fc 100%);
        color: #1d4ed8 !important;
        box-shadow: 0 2px 8px rgba(80, 112, 168, 0.11);
        transform: scale(1.035);
    }
    .custom-nav-link i {
        color: #6366f1 !important;
        min-width: 22px;
        text-align: center;
    }
    .sidenav-header {
        padding-bottom: 0.5rem;
    }
    .custom-userbox {
        background: #eef2ff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(80,112,168,0.07);
        transition: box-shadow 0.2s;
    }
    .custom-userbox:hover {
        box-shadow: 0 4px 16px rgba(80,112,168,0.13);
    }
    .sidenav-footer {
        border-top: 1px solid #e5e7eb;
        background: transparent;
        border-radius: 0 0 1.5rem 1.5rem;
    }
</style>
