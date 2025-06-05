<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main" data-color="primary">
    <div class="sidenav-header">
        <a class="navbar-brand m-0" href="/dashboard">
            <img src="/argon/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Libralink2</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard"><i class="fas fa-chart-line"></i><span class="nav-link-text ms-1">Dashboard</span></a></li>
            @if(auth()->user()->role === 'Admin')
                <li class="nav-item"><a class="nav-link {{ request()->is('categories') ? 'active' : '' }}" href="/categories"><i class="fas fa-tags"></i><span class="nav-link-text ms-1">Categories</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('books*') ? 'active' : '' }}" href="/books"><i class="fas fa-book"></i><span class="nav-link-text ms-1">Books</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('members*') ? 'active' : '' }}" href="/members"><i class="fas fa-users"></i><span class="nav-link-text ms-1">Members</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('borrow*') ? 'active' : '' }}" href="/borrow"><i class="fas fa-book-reader"></i><span class="nav-link-text ms-1">Borrow</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}" href="/announcements"><i class="fas fa-bullhorn"></i><span class="nav-link-text ms-1">Announcements</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('reviews*') ? 'active' : '' }}" href="/reviews"><i class="fas fa-star"></i><span class="nav-link-text ms-1">Reviews</span></a></li>
            @elseif(auth()->user()->role === 'Member')
                <li class="nav-item"><a class="nav-link {{ request()->is('books*') ? 'active' : '' }}" href="/books"><i class="fas fa-book"></i><span class="nav-link-text ms-1">Books</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('borrow*') ? 'active' : '' }}" href="/borrow"><i class="fas fa-book-reader"></i><span class="nav-link-text ms-1">Borrow</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}" href="/announcements"><i class="fas fa-bullhorn"></i><span class="nav-link-text ms-1">Announcements</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('bookmarks*') ? 'active' : '' }}" href="/bookmarks"><i class="fas fa-bookmark"></i><span class="nav-link-text ms-1">Bookmarks</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('wishlists*') ? 'active' : '' }}" href="/wishlists"><i class="fas fa-heart"></i><span class="nav-link-text ms-1">Wishlists</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('bookcollection*') ? 'active' : '' }}" href="/bookcollection"><i class="fas fa-layer-group"></i><span class="nav-link-text ms-1">Collection</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('reviews*') ? 'active' : '' }}" href="/reviews"><i class="fas fa-star"></i><span class="nav-link-text ms-1">Reviews</span></a></li>
            @endif
            <li class="nav-item"><a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="/profile"><i class="fas fa-user"></i><span class="nav-link-text ms-1">Profile</span></a></li>
        </ul>
    </div>
</aside>
