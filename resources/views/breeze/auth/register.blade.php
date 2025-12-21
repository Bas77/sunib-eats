<x-guest-layout>
    <div class="text-center mb-5">
        <h3 class="fw-bold text-dark">Buat Akun Baru</h3>
        <p class="text-secondary">Lengkapi data diri untuk mulai menjelajah kuliner!</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row g-3">

            {{-- Name --}}
            <div class="col-12">
                <div class="form-floating">
                    <input type="text" class="form-control shadow-sm rounded-3 @error('name') is-invalid @enderror"
                        id="name" name="name" placeholder="Nama Lengkap"
                        value="{{ old('name') }}" required autofocus autocomplete="name">
                    <label for="name" class="text-muted"><i class="bi bi-person me-1"></i> Nama Lengkap</label>
                    @error('name')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="email" class="form-control shadow-sm rounded-3 @error('email') is-invalid @enderror"
                        id="email" name="email" placeholder="name@example.com"
                        value="{{ old('email') }}" required autocomplete="username">
                    <label for="email" class="text-muted"><i class="bi bi-envelope me-1"></i> Email Address</label>
                    @error('email')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Phone --}}
            <div class="col-md-6">
                <div class="form-floating"> 
                    <input type="text" class="form-control shadow-sm rounded-3 @error('phone_number') is-invalid @enderror"
                        id="phone_number" name="phone_number" placeholder="0812..."
                        value="{{ old('phone_number') }}" required>
                    <label for="phone_number" class="text-muted"><i class="bi bi-telephone me-1"></i> Nomor Telepon</label>
                    @error('phone_number')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Date of Birth --}}
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="date" class="form-control shadow-sm rounded-3 @error('date_of_birth') is-invalid @enderror"
                        id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth') }}" required>
                    <label for="date_of_birth" class="text-muted"><i class="bi bi-calendar-event me-1"></i> Tanggal Lahir</label>
                    @error('date_of_birth')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Gender --}}
            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-select shadow-sm rounded-3 @error('gender') is-invalid @enderror" id="gender" name="gender">
                        <option value="" selected disabled>Pilih Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    <label for="gender" class="text-muted"><i class="bi bi-gender-ambiguous me-1"></i> Jenis Kelamin</label>
                    @error('gender')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="col-12">
                <div class="form-floating">
                    <input type="password" class="form-control shadow-sm rounded-3 @error('password') is-invalid @enderror"
                        id="password" name="password" placeholder="Password"
                        required autocomplete="new-password">
                    <label for="password" class="text-muted"><i class="bi bi-lock me-1"></i> Password</label>
                    @error('password')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="col-12">
                <div class="form-floating">
                    <input type="password" class="form-control shadow-sm rounded-3 @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password"
                        required autocomplete="new-password">
                    <label for="password_confirmation" class="text-muted"><i class="bi bi-check-circle me-1"></i> Ulangi Password</label>
                    @error('password_confirmation')
                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        <div class="d-grid gap-2 mt-5">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow py-3">
                Daftar Sekarang
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">Sudah punya akun?</p>
            <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-primary">Masuk disini</a>
        </div>
    </form>
</x-guest-layout>
