@extends('layouts.app')

@push('styles')
<style>
    /* General container styling for figure-ground distinction */
    .main-content {
        padding: 2rem 0;
        /* background-color: #f8f9fa; */
        min-height: 100vh;
    }

    /* Card styling for proximity and closure */
    .details-card, .review-card, .review-form-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }

    .details-card:hover, .review-card:hover, .review-form-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    /* Restaurant image styling */
    .restaurant-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        max-height: 350px;
    }

    /* Review image styling */
    .review-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        transition: transform 0.3s ease;
    }

    .review-image:hover {
        transform: scale(1.05);
    }

    /* Vote button styling for similarity */
    .vote-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
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

    /* Review form styling */
    .review-form-card {
        border: 1px solid #dee2e6;
    }

    .review-form-card h3 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
    }

    /* Star rating styling */
    .star-rating {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 1.5rem;
        color: #dee2e6;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107;
    }

    /* Success message */
    .review-success {
        display: none;
        background-color: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 1rem;
    }

    /* Review card styling for continuity */
    .review-card {
        border-top: 1px solid #dee2e6;
        padding: 1.5rem ;
    }

    .review-card:first-of-type {
        border-top: none;
        padding-top: 0;
    }

    /* Typography for hierarchy */
    h1, h2, h3 {
        font-weight: 600;
        color: #343a40;
    }

    .text-muted {
        font-size: 0.9rem;
    }

    /* Nearby restaurants */
    .nearby-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .nearby-card:hover {
        transform: translateY(-4px);
    }

    .nearby-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container main-content">
    <a href="{{ url('/') }}" class="btn btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left"></i> Back to Restaurants
    </a>

    <div class="row g-4">
        <!-- Left Column: Image and Location -->
        <div class="col-lg-5">
            <div class="details-card">
                <img src="{{ $restaurant->image_url }}" class="restaurant-image" alt="{{ $restaurant->name }}" aria-label="Image of {{ $restaurant->name }}">
                
                @if ($restaurant->latitude && $restaurant->longitude)
                    <h3 class="mb-3">Location</h3>
                    <p class="fs-5 mb-3"><i class="bi bi-geo-alt-fill me-2"></i>{{ $restaurant->address }}</p>
                    <div class="ratio ratio-16x9" style="border-radius: .5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q={{ $restaurant->latitude }},{{ $restaurant->longitude }}&hl=en&z=14&output=embed" 
                            allowfullscreen 
                            loading="lazy" 
                            title="Map location of {{ $restaurant->name }}">
                        </iframe>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Details, Review Form, and Reviews -->
        <div class="col-lg-7">
            <div class="details-card">
                <h1 class="mb-2">{{ $restaurant->name }}</h1>
                <p class="text-muted mb-2">{{ $restaurant->cuisine }} • {{ $restaurant->area }}</p>
                <p class="fs-5"><i class="bi bi-star-fill text-warning me-2"></i>{{ number_format($restaurant->reviews->avg('rating'), 1) }} ({{ $restaurant->reviews->count() }} reviews)</p>
                <hr class="my-4">

                <!-- Review Form (Non-Database) -->
                @auth
                    <div class="review-form-card">
                        <h3>Add Your Review</h3>
                        <form id="review-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <div class="star-rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                        <label for="star{{ $i }}" title="{{ $i }} stars"><i class="bi bi-star-fill"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Your Review</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4" required placeholder="Share your experience..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Meal Price (Rp, optional)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="1000" placeholder="e.g., 50000">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image (optional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                        <div id="review-success" class="review-success">
                            Review submitted! (This is a simulation without database interaction.)
                        </div>
                    </div>
                @else
                    <div class="review-form-card text-center">
                        <p class="text-muted">Please <a href="{{ route('login') }}">log in</a> to add a review.</p>
                    </div>
                @endauth

                <h2 class="mb-4 mt-5">Student Reviews</h2>
                @forelse ($reviews as $review)
                    <div class="review-card">
                        <div class="d-flex align-items-center mb-2">
                            <strong class="me-auto">{{ $review->user->name }}</strong>
                            <span class="text-warning fw-bold"><i class="bi bi-star-fill"></i> {{ number_format($review->rating, 1) }}</span>
                        </div>
                        
                        @if($review->price)
                            <p class="mb-2">
                                <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">
                                    Meal Price: Rp {{ number_format($review->price, 0, ',', '.') }}
                                </span>
                            </p>
                        @endif

                        <p class="mb-3 text-muted">"{{ $review->comment }}"</p>
                        
                        @if($review->image_url)
                            <a href="{{ $review->image_url }}" target="_blank" aria-label="View review image">
                                <img src="{{ $review->image_url }}" class="review-image" alt="Review image for {{ $review->user->name }}'s review">
                            </a>
                        @endif

                        @auth
                            <div class="mt-3 d-flex align-items-center gap-2">
                                @php
                                    $userVote = $userVotes[$review->id] ?? null;
                                @endphp
                                <button class="vote-btn up @if($userVote === 'up') active @endif" 
                                        data-review-id="{{ $review->id }}" 
                                        data-vote-type="up" 
                                        aria-label="Upvote review">
                                    <i class="bi bi-hand-thumbs-up-fill"></i> 
                                    <span id="upvotes-{{ $review->id }}">{{ $review->upvotes }}</span>
                                </button>
                                <button class="vote-btn down @if($userVote === 'down') active @endif" 
                                        data-review-id="{{ $review->id }}" 
                                        data-vote-type="down" 
                                        aria-label="Downvote review">
                                    <i class="bi bi-hand-thumbs-down-fill"></i> 
                                    <span id="downvotes-{{ $review->id }}">{{ $review->downvotes }}</span>
                                </button>
                            </div>
                        @endauth

                        @guest
                            <div class="mt-3">
                                <p class="text-muted"><a href="{{ route('login') }}">Log in</a> to vote on this review.</p>
                            </div>
                        @endguest
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <p class="mb-0">No reviews yet. Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Nearby Restaurants Section -->
    @if($nearby->isNotEmpty())
        <div class="mt-5">
            <h2 class="mb-4">Nearby Restaurants</h2>
            <div class="row g-4">
                @foreach($nearby as $nearbyRestaurant)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('restaurants.show', $nearbyRestaurant->id) }}" class="nearby-card text-decoration-none">
                            <img src="{{ $nearbyRestaurant->image_url }}" alt="{{ $nearbyRestaurant->name }}" aria-label="Image of {{ $nearbyRestaurant->name }}">
                            <h4 class="mb-1">{{ $nearbyRestaurant->name }}</h4>
                            <p class="text-muted mb-0">{{ $nearbyRestaurant->cuisine }} • {{ $nearbyRestaurant->area }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle review form submission (client-side simulation)
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reviewData = {
                rating: formData.get('rating'),
                comment: formData.get('comment'),
                price: formData.get('price'),
                image: formData.get('image') ? 'Image selected' : null
            };
            console.log('Review submitted:', reviewData);
            const successMessage = document.getElementById('review-success');
            successMessage.style.display = 'block';
            this.reset();
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        });
    }

    // Voting functionality (unchanged)
    const votedReviewsThisSession = new Set();

    document.querySelectorAll('.vote-btn').forEach(button => {
        const reviewId = button.dataset.reviewId;
        const voteType = button.dataset.voteType;
        const isActive = button.classList.contains('active');
        console.log(`Review ${reviewId}, Vote Type: ${voteType}, Active: ${isActive}`);
    });

    document.querySelectorAll('.vote-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const reviewId = this.dataset.reviewId;
            const voteType = this.dataset.voteType;

            if (!reviewId || !voteType) {
                console.error('Missing reviewId or voteType:', { reviewId, voteType });
                alert('Error: Unable to process vote. Please try again.');
                return;
            }

            if (votedReviewsThisSession.has(reviewId)) {
                return;
            }
            votedReviewsThisSession.add(reviewId);

            fetch(`/reviews/${reviewId}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include',
                body: JSON.stringify({ vote_type: voteType })
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 401) {
                        window.location.href = '/login';
                    } else if (response.status === 419) {
                        alert('CSRF token mismatch. Please refresh the page and try again.');
                    } else if (response.status === 500) {
                        alert('Server error: Unable to process vote. Please try again later.');
                    } else {
                        alert('An error occurred. Please try again later.');
                    }
                    throw new Error(`Request failed with status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById(`upvotes-${reviewId}`).textContent = data.upvotes;
                document.getElementById(`downvotes-${reviewId}`).textContent = data.downvotes;

                const upBtn = document.querySelector(`.vote-btn.up[data-review-id="${reviewId}"]`);
                const downBtn = document.querySelector(`.vote-btn.down[data-review-id="${reviewId}"]`);
                const currentVote = upBtn.classList.contains('active') ? 'up' : (downBtn.classList.contains('active') ? 'down' : null);

                if (currentVote === voteType) {
                    this.classList.remove('active');
                } else {
                    upBtn.classList.remove('active');
                    downBtn.classList.remove('active');
                    this.classList.add('active');
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                votedReviewsThisSession.delete(reviewId);
            });
        });
    });
});
</script>
@endpush