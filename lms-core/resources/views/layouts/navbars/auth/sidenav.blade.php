<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}"
            target="_blank">
            <img src="{{ asset('img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Libralink</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <!-- LMS Core Navigation -->
            <li class="nav-item"><a class="nav-link" href="/categories"><i class="fas fa-tags"></i><span class="nav-link-text ms-1">Categories</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/books"><i class="fas fa-book"></i><span class="nav-link-text ms-1">Books</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/members"><i class="fas fa-users"></i><span class="nav-link-text ms-1">Members</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/borrow"><i class="fas fa-book-reader"></i><span class="nav-link-text ms-1">Borrow</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/announcements"><i class="fas fa-bullhorn"></i><span class="nav-link-text ms-1">Announcements</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/bookmarks"><i class="fas fa-bookmark"></i><span class="nav-link-text ms-1">Bookmarks</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/reviews"><i class="fas fa-star"></i><span class="nav-link-text ms-1">Reviews</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/wishlists"><i class="fas fa-heart"></i><span class="nav-link-text ms-1">Wishlists</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/bookcollection"><i class="fas fa-layer-group"></i><span class="nav-link-text ms-1">Collection</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/reports"><i class="fas fa-chart-pie"></i><span class="nav-link-text ms-1">Reports</span></a></li>
            <!-- End LMS Core Navigation -->
        </ul>
    </div>
</aside>
