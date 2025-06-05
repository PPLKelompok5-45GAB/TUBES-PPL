<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 448 512%22><path fill=%22%23fd7e14%22 d=%22M352 96c0-53.02-42.98-96-96-96s-96 42.98-96 96 42.98 96 96 96 96-42.98 96-96zM233.59 241.1c-59.33-36.32-155.43-46.3-203.79-49.05C13.55 191.13 0 203.51 0 219.14v222.8c0 14.33 11.59 26.28 26.49 27.05 43.66 2.29 131.99 10.68 193.04 41.43 9.37 4.72 20.48-1.71 20.48-11.87V252.56c-.01-4.67-2.32-8.95-6.42-11.46zm248.61-49.05c-48.35 2.74-144.46 12.73-203.78 49.05-4.1 2.51-6.41 6.96-6.41 11.63v245.79c0 10.19 11.14 16.63 20.54 11.9 61.04-30.72 149.32-39.11 192.97-41.4 14.9-.78 26.49-12.73 26.49-27.06V219.14c-.01-15.63-13.56-28.01-29.81-27.09z%22></path></svg>">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 448 512%22><path fill=%22%23fd7e14%22 d=%22M352 96c0-53.02-42.98-96-96-96s-96 42.98-96 96 42.98 96 96 96 96-42.98 96-96zM233.59 241.1c-59.33-36.32-155.43-46.3-203.79-49.05C13.55 191.13 0 203.51 0 219.14v222.8c0 14.33 11.59 26.28 26.49 27.05 43.66 2.29 131.99 10.68 193.04 41.43 9.37 4.72 20.48-1.71 20.48-11.87V252.56c-.01-4.67-2.32-8.95-6.42-11.46zm248.61-49.05c-48.35 2.74-144.46 12.73-203.78 49.05-4.1 2.51-6.41 6.96-6.41 11.63v245.79c0 10.19 11.14 16.63 20.54 11.9 61.04-30.72 149.32-39.11 192.97-41.4 14.9-.78 26.49-12.73 26.49-27.06V219.14c-.01-15.63-13.56-28.01-29.81-27.09z%22></path></svg>" type="image/svg+xml">
    <title>
        Libralink
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <!-- Custom Styles -->
    <link href="{{ asset('argon/css/pagination-custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/css/table-custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/css/search-filter-custom.css') }}" rel="stylesheet" />
</head>

<body class="{{ $class ?? '' }}">

    <div id="app">
        <!-- Enhanced Notification System -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            <!-- Session Status -->
            @if(session('status'))
                <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                    <div class="toast-header bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong class="me-auto">Success</strong>
                        <small>Just now</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            
            <!-- Session Error -->
            @if(session('error'))
                <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                    <div class="toast-header bg-danger text-white">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong class="me-auto">Error</strong>
                        <small>Just now</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <!-- Overdue Books Notification -->
            @auth
                @if(auth()->user()->role === 'Member' && auth()->user()->member)
                    @php
                        $overdueCount = App\Models\Log_Pinjam_Buku::where('member_id', auth()->user()->member->member_id)
                                        ->where('status', 'overdue')
                                        ->count();
                    @endphp
                    
                    @if($overdueCount > 0)
                        <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                            <div class="toast-header bg-warning text-dark">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong class="me-auto">Overdue Books</strong>
                                <small>Important</small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                You have {{ $overdueCount }} overdue book(s). Please return them as soon as possible.
                                <div class="mt-2 pt-2 border-top">
                                    <a href="{{ route('borrow.index') }}" class="btn btn-warning btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                
                @if(auth()->user()->role === 'Admin')
                    @php
                        try {
                            $pendingCount = App\Models\Log_Pinjam_Buku::where('status', 'pending')->count();
                        } catch (\Exception $e) {
                            // Handle case when table doesn't exist during tests
                            $pendingCount = 0;
                        }
                    @endphp
                    
                    @if($pendingCount > 0)
                        <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                            <div class="toast-header bg-info text-white">
                                <i class="fas fa-bell me-2"></i>
                                <strong class="me-auto">Pending Requests</strong>
                                <small>Admin Action</small>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                There are {{ $pendingCount }} pending borrow request(s) awaiting your approval.
                                <div class="mt-2 pt-2 border-top">
                                    <a href="{{ route('borrow.index') }}" class="btn btn-primary btn-sm">Review Requests</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endauth
        </div>
    </div>

    @guest
        @yield('content')
    @endguest

    @auth
        @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'login', 'register']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
            @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('{{ asset('img/profile-layout-header.jpg') }}'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
            @include('layouts.navbars.auth.sidenav')
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @include('components.fixed-plugin')
        @endif
    @endauth

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>
    @stack('js')
    @stack('scripts')

<!-- Toast Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
            autohide: false
        });
    });
    
    // Add debounce functionality for search inputs
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
    
    // Apply debounce to search inputs
    const searchInputs = document.querySelectorAll('input[name="search"]');
    searchInputs.forEach(input => {
        const form = input.closest('form');
        if (form && !form.classList.contains('debounce-applied')) {
            const originalSubmit = form.onsubmit;
            form.classList.add('debounce-applied');
            
            input.addEventListener('input', debounce(function() {
                if (input.value.length >= 3 || input.value.length === 0) {
                    form.requestSubmit();
                }
            }, 500));
        }
    });
});
</script>
</body>

</html>
