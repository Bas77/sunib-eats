@extends('layouts.app')

@push('styles')
<style>
    /* ... your existing styles ... */

    .restaurant-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-radius: .75rem;
        margin-bottom: 1.5rem;
        max-height: 350px;
        margin: 0 auto 1.5rem;
    }
    .review-card:first-of-type {
        border-top: none;
        padding-top: 0;
    }
    .vote-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .vote-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .vote-btn.active.up {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
    .vote-btn.active.down {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    /* 👇 ADD THIS NEW STYLE 👇 */
    .review-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: .5rem;
    }
</style>
@endpush

@section('content')
{{-- Bootstrap CDN links --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container main-content">
    <div class="row g-4">
        {{-- Left Column: Image and Location --}}
<div class="col-lg-5">
    <div class="details-card">
        <img src="{{ $restaurant->image_url }}" class="restaurant-image" alt="{{ $restaurant->name }}">
        
        {{-- 👇 WRAP THE ENTIRE LOCATION BLOCK IN THIS @if STATEMENT 👇 --}}
        @if ($restaurant->latitude && $restaurant->longitude)
            <h3 class="mb-3">Location</h3>
            <p class="fs-5 mb-3"><i class="bi bi-geo-alt-fill me-2"></i>{{ $restaurant->address }}</p>
            <div class="ratio ratio-16x9" style="border-radius: .5rem;">
                <iframe 
                    src="https://maps.google.com/maps?q={{ $restaurant->latitude }},{{ $restaurant->longitude }}&hl=es;z=14&output=embed" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        @endif
        
    </div>
</div>
        {{-- Right Column: Details and Reviews --}}
        <div class="col-lg-7">
            <div class="details-card">
                <div class="mb-4">
                    <h1 class="display-5 fw-bold">{{ $restaurant->name }}</h1>
                    <p class="fs-5 text-muted">{{ $restaurant->cuisine }} · {{ $restaurant->area }}</p>
                    <p class="lead">{{ $restaurant->description ?? 'No description available.' }}</p>
                </div>
                <hr class="my-4">

                <h2 class="mb-4">Student Reviews</h2>
                
                {{-- Review 1 (With Image) --}}
                <div class="review-card">
                    <div class="d-flex align-items-center mb-2">
                        <strong class="me-auto">Jane Doe</strong>
                        <span class="text-warning fw-bold"><i class="bi bi-star-fill"></i> 5.0</span>
                    </div>
                    <p class="mb-2"><span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">Meal Price: Rp 25,000</span></p>
                    <p class="mb-3 text-muted">"Amazing food! The best geprek in town. A must-try for students!"</p>
                    
                    {{-- Optional Image --}}
                    <a href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHCPIt4C3iHqzE19rkhKwEjtmL3ca8ysUFBA&s" target="_blank">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHCPIt4C3iHqzE19rkhKwEjtmL3ca8ysUFBA&s" class="review-image" alt="Review image">
                    </a>

                    <div class="mt-3 d-flex align-items-center gap-2">
                        <button class="vote-btn up" data-review-id="1" data-vote-type="up">
                            <i class="bi bi-hand-thumbs-up-fill"></i> <span id="upvotes-1">12</span>
                        </button>
                        <button class="vote-btn down" data-review-id="1" data-vote-type="down">
                            <i class="bi bi-hand-thumbs-down-fill"></i> <span id="downvotes-1">1</span>
                        </button>
                    </div>
                </div>
                
                {{-- Review 2 (No Image) --}}
                <div class="review-card">
                    <div class="d-flex align-items-center mb-2">
                        <strong class="me-auto">John Smith</strong>
                        <span class="text-warning fw-bold"><i class="bi bi-star-fill"></i> 4.0</span>
                    </div>
                    <p class="mb-2"><span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">Meal Price: Rp 28,000</span></p>
                    <p class="mb-3 text-muted">"Good value for the price, but can be a bit crowded during lunch hour."</p>
                    <div class="d-flex align-items-center gap-2">
                        <button class="vote-btn up" data-review-id="2" data-vote-type="up">
                            <i class="bi bi-hand-thumbs-up-fill"></i> <span id="upvotes-2">8</span>
                        </button>
                        <button class="vote-btn down" data-review-id="2" data-vote-type="down">
                            <i class="bi bi-hand-thumbs-down-fill"></i> <span id="downvotes-2">3</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Nearby Restaurants Section --}}
    {{-- ... your nearby restaurants code ... --}}
</div>
@endsection

{{-- Voting logic --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const votedReviews = {}; // Tracks votes: { '1': 'up', '2': 'down' }

    document.querySelectorAll('.vote-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const reviewId = this.dataset.reviewId;
            const voteType = this.dataset.voteType;
            const currentVote = votedReviews[reviewId];

            const upBtn = document.querySelector(`.vote-btn.up[data-review-id="${reviewId}"]`);
            const downBtn = document.querySelector(`.vote-btn.down[data-review-id="${reviewId}"]`);
            const upvotesSpan = document.getElementById(`upvotes-${reviewId}`);
            const downvotesSpan = document.getElementById(`downvotes-${reviewId}`);

            let upvotes = parseInt(upvotesSpan.textContent);
            let downvotes = parseInt(downvotesSpan.textContent);

            if (currentVote === voteType) {
                if (voteType === 'up') {
                    upvotesSpan.textContent = upvotes - 1;
                    upBtn.classList.remove('active');
                } else {
                    downvotesSpan.textContent = downvotes - 1;
                    downBtn.classList.remove('active');
                }
                delete votedReviews[reviewId];
                return;
            }

            if (currentVote) {
                if (currentVote === 'up') {
                    upvotesSpan.textContent = upvotes - 1;
                    upBtn.classList.remove('active');
                } else {
                    downvotesSpan.textContent = downvotes - 1;
                    downBtn.classList.remove('active');
                }
            }
            
            if (voteType === 'up') {
                upvotesSpan.textContent = upvotes + 1;
                upBtn.classList.add('active');
            } else {
                downvotesSpan.textContent = downvotes + 1;
                downBtn.classList.add('active');
            }
            votedReviews[reviewId] = voteType;
        });
    });
});
</script>
@endpush