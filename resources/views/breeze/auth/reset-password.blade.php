<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Reset Password</h4>
        <p class="text-muted small">
            Masukkan password baru untuk akun Anda.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="form-floating mb-3">
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                placeholder="name@example.com"
                required
                autofocus
                autocomplete="username"
                readonly>
            <label for="email">Email Address</label>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-floating mb-3">
            <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                placeholder="Password Baru"
                required
                autocomplete="new-password">
            <label for="password">Password Baru</label>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-floating mb-4">
            <input
                type="password"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Konfirmasi Password"
                required
                autocomplete="new-password">
            <label for="password_confirmation">Konfirmasi Password</label>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                Reset Password
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="small fw-bold text-decoration-none">
                Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>