@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-4x text-warning mb-3"></i>
                        <h1 class="display-1 font-weight-bold">403</h1>
                        <h4 class="mb-4">Access Denied</h4>
                        <p class="text-muted mb-4">You don't have permission to access this resource. Please contact your administrator if you believe this is an error.</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Go Back
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
