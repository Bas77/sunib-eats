@props(['restaurant'])

<div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Tulis Ulasan</h5>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @auth
        <form action="{{ route('reviews.store', $restaurant) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label small text-muted">Rating Anda</label>
                <select name="rating" class="form-select">
                    <option value="5">⭐⭐⭐⭐⭐ (5 - Sempurna)</option>
                    <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                    <option value="3">⭐⭐⭐ (3 - Biasa)</option>
                    <option value="2">⭐⭐ (2 - Kurang)</option>
                    <option value="1">⭐ (1 - Buruk)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Estimasi Harga per Orang</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">Rp</span>
                    <input type="number"
                        name="price_per_portion"
                        class="form-control"
                        value="{{ old('price_per_portion') }}"
                        placeholder="Contoh: 25000"
                        min="1000"
                        required>
                </div>
                @error('price_per_portion')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Pengalaman Anda</label>
                <textarea name="content" rows="3" class="form-control" placeholder="Ceritakan pengalaman makannya..." required>{{ old('content') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Foto Makanan (Maks 3)</label>

                <div class="d-flex gap-2 flex-wrap" id="image-upload-container">
                    <div class="upload-box" id="add-image-btn">
                        <i class="bi bi-plus-lg text-secondary fs-4"></i>
                    </div>
                </div>

                <input type="file"
                    id="final-upload-input"
                    name="images[]"
                    style="opacity: 0; position: absolute; z-index: -1; width: 0; height: 0;"
                    multiple>

                <input type="file"
                    id="file-selector"
                    accept="image/*"
                    multiple
                    style="opacity: 0; position: absolute; z-index: -1; width: 0; height: 0;">

                <div class="form-text small text-muted mt-2">Format: JPG, PNG. Maks 5MB/foto.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Ulasan</button>
        </form>
        @else
        <div class="text-center py-4 bg-light rounded">
            <p class="small text-muted mb-3">Login untuk mulai mereview!</p>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4 fw-bold">Masuk / Daftar</a>
        </div>
        @endauth
    </div>
</div>