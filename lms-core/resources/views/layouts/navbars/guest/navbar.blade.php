<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav
                class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid">
                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{ route('home') }}">
                        <span id="brand-slider" class="brand-slider">Libralink: The Intelligent Library</span>
                    </a>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav ms-auto">
                            @guest
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ route('home') }}">
                                    <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('register') }}">
                                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                    Sign Up
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('login') }}">
                                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                    Login
                                </a>
                            </li>
                            @endguest
                            @auth
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt opacity-6 text-dark me-1"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link me-2" style="padding:0;">Logout</button>
                                </form>
                            </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
<style>
.brand-slider {
    display: inline-block;
    min-width: 250px;
    animation: brandSlider 8s linear infinite;
    white-space: nowrap;
    font-size: 1.25rem;
    font-weight: bold;
    background: linear-gradient(90deg, #1e90ff 0%, #23d160 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
@keyframes brandSlider {
    0%   { content: "Libralink: The Intelligent Library"; }
    25%  { content: "Libralink: The Intelligent Library"; }
    100% { content: "Libralink: The Intelligent Library"; }
}
</style>
<script>
// Intuitive slider for brand
const slogans = [
    "Libralink: The Intelligent Library"
];
let sloganIndex = 0;
setInterval(() => {
    sloganIndex = (sloganIndex + 1) % slogans.length;
    document.getElementById('brand-slider').textContent = slogans[sloganIndex];
}, 3500);
</script>
