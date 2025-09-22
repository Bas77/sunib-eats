{{-- in resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@push('styles')
<style> 

    .login-container {
        /* No longer a grid for two columns, just a single centered panel */
        display: flex; /* Changed to flex for easier centering of content */
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Take full viewport height */
        width: 100%; /* Take full width */
        margin-top: 1rem;
    }
    .login-form-panel {
        /* Styles for the form container */
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        background: #fff;
        border-radius: 0.75rem; /* Soften edges of the card */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        max-width: 500px; /* Limit max width for the form */
        width: 90%; /* Responsive width */
        margin: auto; /* Center the panel itself within the flex container */
    }
    /* Removed .login-image-panel styles as it's no longer present */

    .form-control-lg {
        padding: 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }
    .btn-primary-gradient {
        background: linear-gradient(135deg, #4a90e2, #2552a8); /* Using explicit gradient */
        border: none;
        transition: opacity 0.2s ease-in-out;
    }
    .btn-primary-gradient:hover {
        opacity: 0.9;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) { /* Adjust breakpoint for smaller screens */
        .login-form-panel {
            padding: 2.5rem; /* Reduce padding on smaller screens */
            width: 95%;
        }
    }
</style>
@endpush

@section('content')

<div class="login-container">
    {{-- Image Panel is removed --}}

    {{-- Form Panel (now centered) --}}
    <div class="login-form-panel">
        <div class="w-100"> {{-- Removed fixed max-width, now controlled by .login-form-panel --}}
            <h1 class="display-5 fw-bold mb-2 text-center">Welcome Back</h1>
            <p class="text-muted mb-5 text-center">Find the best student-friendly eats near you.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Group 1: Email Input (Proximity) --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Group 2: Password Input (Proximity) --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        @if (Route::has('password.request'))
                            <a class="form-text" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                     @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Group 3: Remember Me (Proximity) --}}
                <div class="mb-4 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                
                {{-- Group 4: Actions (Focal Point & Proximity) --}}
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-gradient text-white btn-lg fw-bold">
                        Login
                    </button>
                </div>

                <p class="text-center text-muted">
                    Don't have an account? <a href="{{ route('register') }}">Sign up now</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection