@props(['categories', 'action' => route('home')])

<button class="btn btn-primary w-100 d-lg-none mb-3 d-flex justify-content-between align-items-center"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#filterCollapse"
    aria-expanded="false"
    aria-controls="filterCollapse">
    <span><i class="bi bi-funnel-fill"></i> Filter </span>
    <i class="bi bi-chevron-down"></i>
</button>

<div class="collapse d-lg-block" id="filterCollapse">
    <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 d-none d-lg-block"><i class="bi bi-funnel-fill text-primary"></i> Filter</h5>

            <form action="{{ $action }}" method="GET">
                @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3">Harga</h6>

                    @php
                    $prices = [
                    '1' => 'Cheap ($)',
                    '2' => 'Affordable ($$)',
                    '3' => 'Pricy ($$$)',
                    '4' => 'Premium ($$$$)',
                    '5' => 'Luxury ($$$$$)'
                    ];
                    @endphp

                    @foreach($prices as $value => $label)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="price" value="{{ $value }}"
                            id="price-{{ $value }}"
                            {{ request('price') == $value ? 'checked' : '' }}>
                        <label class="form-check-label small" for="price-{{ $value }}">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>

                @if(isset($categories))
                @foreach($categories as $type => $catList)
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3">
                        {{ str_replace('_', ' ', $type) }}
                    </h6>
                    <div class="row g-2">
                        @foreach($catList as $cat)
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="categories[]"
                                    value="{{ $cat->id }}"
                                    id="cat-{{ $cat->id }}"
                                    {{ in_array($cat->id, request('categories', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark small text-truncate d-block" for="cat-{{ $cat->id }}" title="{{ ucwords($cat->name) }}">
                                    {{ ucwords($cat->name) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <hr class="text-muted opacity-25">
                @endforeach
                @endif

                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3">Urutkan</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sortNew" value="newest" {{ request('sort') == 'newest' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="sortNew">Paling Baru</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sortRating" value="rating_desc" {{ request('sort') == 'rating_desc' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="sortRating">Rating Tertinggi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sortCheap" value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="sortCheap">Harga Terendah</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Terapkan</button>
                    @if(request()->hasAny(['search', 'sort', 'categories', 'price']))
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>