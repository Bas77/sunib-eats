<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4 class="fw-bold mb-0">Edit Ulasan</h4>
                        <p class="text-muted small mb-0">
                            Untuk: <span class="fw-bold text-dark">{{ $review->restaurant->name }}</span>
                        </p>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small text-muted">Rating</label>
                                <select name="rating" class="form-select">
                                    @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} Bintang {{ $i==5 ? '(Sempurna)' : '' }}
                                    </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted">Ulasan Anda</label>
                                <textarea name="content" rows="5" class="form-control" required>{{ old('content', $review->content) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted">Foto Makanan (Maks 3)</label>

                                <div class="d-flex gap-2 flex-wrap" id="image-upload-container">

                                    @foreach($review->images as $img)
                                    <div class="preview-box existing-image"
                                        style="background-image: url('{{ 
                                            Str::startsWith($img->path, 'http') 
                                                ? $img->path 
                                                : Storage::url($img->path) 
                                        }}');">

                                        <div class="remove-btn" onclick="removeExistingImage(this, '{{ $img->id }}')">
                                            <i class="bi bi-x"></i>
                                        </div>

                                        <input type="hidden" name="deleted_images[]" value="" class="deleted-image-input">
                                    </div>
                                    @endforeach

                                    <div class="upload-box" id="add-image-btn">
                                        <i class="bi bi-plus-lg text-secondary fs-4"></i>
                                    </div>
                                </div>

                                <input type="file   " id="final-upload-input" name="new_images[]" class="d-none" multiple>
                                <input type="file" id="file-selector" accept="image/*" multiple class="d-none">

                                <div class="form-text small text-muted mt-2">Format: JPG, PNG. Maks 2MB/foto.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('history') }}" class="btn btn-light w-50 fw-bold">Batal</a>
                                <button type="submit" class="btn btn-primary w-50 fw-bold">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>