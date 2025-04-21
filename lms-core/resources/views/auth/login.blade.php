@extends('layouts.app')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Login</h4>
                                    <p class="mb-0">Enter your email and password to sign in</p>
                                </div>
                                <div class="card-body small" style="font-size:0.95rem;">
                                    <form method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') ?? 'admin@argon.com' }}" aria-label="Email">
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" aria-label="Password" value="secret" >
                                            @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Login</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-xs mx-auto" style="font-size:0.95em;">
                                        Don't have an account?
                                        <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column" style="min-width:540px; max-width:700px;">
                            <div id="carouselCards" class="carousel slide position-relative h-100 m-3 px-4 border-radius-lg d-flex flex-column justify-content-center" data-bs-ride="carousel" style="min-width:460px; max-width:620px; width:535px; height:100vh; min-height:100vh; max-height:100vh; overflow:hidden;">
                                <!-- Carousel hint button removed -->
                                <div class="carousel-inner h-100">
                                    <div class="carousel-item active h-100">
                                        <div class="card bg-gradient-primary text-white h-100 d-flex flex-column justify-content-center align-items-center border-0 rounded-4" style="background-image: url('https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80'); background-size:cover; min-width:415px; max-width:535px; min-height:100vh; max-height:100vh;">
                                            <h4 class="mt-5 font-weight-bolder position-relative">Your Knowledge Hub</h4>
                                            <p class="position-relative">Libralink connects you to a world of knowledge.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item h-100">
                                        <div class="card bg-gradient-info text-white h-100 d-flex flex-column justify-content-center align-items-center border-0 rounded-4" style="background-image: url('https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=800&q=80'); background-size:cover; min-width:415px; max-width:535px; min-height:100vh; max-height:100vh;">
                                            <h4 class="mt-5 font-weight-bolder position-relative">Discover Books Instantly</h4>
                                            <p class="position-relative">From the most complete book collection.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item h-100">
                                        <div class="card bg-gradient-success text-white h-100 d-flex flex-column justify-content-center align-items-center border-0 rounded-4" style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80'); background-size:cover; min-width:415px; max-width:535px; min-height:100vh; max-height:100vh;">
                                            <h4 class="mt-5 font-weight-bolder position-relative">Effortless Borrowing</h4>
                                            <p class="position-relative">Borrow and return books with a single click.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Carousel controls: middle left/right edge -->
                                <button class="carousel-control-prev btn btn-dark rounded-circle shadow position-absolute top-50 start-0 translate-middle-y ms-2 z-index-3" type="button" data-bs-target="#carouselCards" data-bs-slide="prev" style="width:48px; height:48px; opacity:0.95;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next btn btn-dark rounded-circle shadow position-absolute top-50 end-0 translate-middle-y me-2 z-index-3" type="button" data-bs-target="#carouselCards" data-bs-slide="next" style="width:48px; height:48px; opacity:0.95;">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

<style>
</style>

<script>
</script>
