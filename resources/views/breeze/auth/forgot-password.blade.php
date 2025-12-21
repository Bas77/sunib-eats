<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Lupa Password?</h4>
        <p class="text-muted small">
            Masukkan email dan nomor HP untuk reset password.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-floating mb-3">
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                placeholder="name@example.com"
                value="{{ old('email') }}"
                required
                autofocus>
            <label for="email">Email Address</label>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-floating mb-4">
            <input
                type="text"
                class="form-control @error('phone_number') is-invalid @enderror"
                id="phone_number"
                name="phone_number"
                placeholder="08xxxxxxxxxx"
                value="{{ old('phone_number') }}"
                required>
            <label for="phone_number">Phone Number</label>
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
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