@extends('layouts.app')

{{-- Custom styles for the new design --}}
@push('styles')
<style>
    /* Hero Section Styling */
    :root {
        --gradient-hero: linear-gradient(135deg, #4a90e2, #2552a8);
    }

    .hero {
        background: var(--gradient-hero);
        padding: 5rem 1rem;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Decorative circles in the background */
    .hero::before, .hero::after {
        content: '';
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    }
    .hero::before {
        width: 200px;
        height: 200px;
        top: -50px;
        left: -50px;
    }
    .hero::after {
        width: 300px;
        height: 300px;
        bottom: -100px;
        right: -100px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }


    /* Search Form Styling */
    .search-form-container {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 800px;
        margin: 2rem auto 0;
    }
    .search-input-group {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 0.5rem;
    }
    .search-input-group i {
        color: #6c757d;
    }
    .search-input-group input {
        border: none;
        outline: none;
        box-shadow: none;
        width: 100%;
    }
    .search-input-group input:focus {
        border: none;
        box-shadow: none;
    }
    .search-divider {
        width: 1px;
        background-color: #e9ecef;
        align-self: stretch;
    }
    .search-form-container .btn-primary {
        background-color: #4a90e2;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: bold;
    }

    /* Filter Tags */
    .filter-tags {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .filter-tag {
        color: white;
        font-size: 0.9rem;
    }

    /* Section title styling */
    .section-title {
        text-align: center;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 1rem; /* Adjusted for subtitle */
    }
    .section-subtitle {
        text-align: center;
        font-size: 1.1rem;
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto 2.5rem;
    }
    .restaurant-card {
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .restaurant-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    /* Add these new styles to your <style> tag */
.card-img-container {
    aspect-ratio: 16 / 10; /* Gives the container a consistent rectangular shape */
    overflow: hidden;
    background-color: #f8f9fa; /* A light background for logos */
}

.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures photos fill the space. Use 'contain' for logos */
}

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero {
            padding: 3rem 1rem;
        }
        .search-form-container {
            flex-direction: column;
            gap: 0.5rem;
            padding: 0.5rem;
        }
        .search-input-group {
            width: 100%;
            padding: 0.5rem;
        }
        .search-divider {
            width: 100%;
            height: 1px;
        }
        .search-form-container .btn-primary {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
{{--
    NOTE: The following lines add Bootstrap CSS to make this page render correctly.
    For best practice, these <link> tags should be moved to the <head> section
    of your main layout file (e.g., resources/views/layouts/app.blade.php).
--}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid p-0">
    {{-- Hero Section with New Design --}}
    <div class="hero">
        <div class="hero-content">
            <h1 class="display-4 fw-bold">Budget-Friendly Food Near Campus</h1>
            <p class="lead">Discover the best affordable eats around campus. Real reviews from students, for students.</p>

            {{-- Search Form --}}
            <form action="{{ route('restaurants.index') }}" method="GET" class="mx-auto">
                <div class="search-form-container">
                    <div class="search-input-group">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control" placeholder="Search restaurants, dishes, or cuisines..." value="{{ request('search') }}">
                    </div>
                    <!-- <div class="search-divider"></div> -->
                    <!-- <div class="search-input-group">
                        <i class="bi bi-geo-alt-fill"></i>
                        <input type="text" name="location" class="form-control" placeholder="Near campus..." value="{{ request('location') }}">
                    </div> -->
                    <button class="btn btn-primary" type="submit">Find Food</button>
                </div>
            </form>

            {{-- Filter Tags --}}
            <div class="filter-tags">
                <span class="filter-tag"><i class="bi bi-check-circle-fill"></i> Student Discounts</span>
                <span class="filter-tag"><i class="bi bi-check-circle-fill"></i> Budget Filters</span>
                <span class="filter-tag"><i class="bi bi-check-circle-fill"></i> Campus Delivery</span>
            </div>
        </div>
    </div>

    {{-- Featured Restaurants Section --}}
    <div class="container my-5 py-5">
        <h2 class="section-title h1">Student Favorites</h2>
        <p class="section-subtitle">
            Top-rated restaurants with the best deals and student-friendly prices
        </p>

        {{-- Restaurant cards section --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @forelse ($restaurants as $restaurant)
                <div class="col d-flex">
                    <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="card h-100 shadow-sm border-0 restaurant-card w-100">
                        
                        <div class="card-img-container">
                            <img src="{{ $restaurant->image_url }}" alt="{{ $restaurant->name }}">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div>
                                <h5 class="card-title fw-bold">{{ $restaurant->name }}</h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $restaurant->area }}  &middot; <i class="bi bi-pin-map-fill"></i> {{ number_format($restaurant->distance, 1) }} km 
                                </p>
                            </div>
                            <div class="d-flex   justify-content-between align-items-center mt-auto pt-3">
                                <span class="fw-bold text-warning">
                                    <i class="bi bi-star-fill"></i> {{ number_format($restaurant->rating, 1) }}
                                </span>
                                <span class="badge bg-light text-dark fs-6">{{ $restaurant->price_range }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p class="mb-0">🍽️ No restaurants found. Please try a different search!</p>
                    </div>
                </div>
            @endforelse
        </div>  

        <div class="text-center mt-5">
            <a href="{{ route('restaurants.index') }}" class="btn btn-lg btn-primary px-5" style="background: var(--gradient-hero);">
                View All Restaurants
            </a>
        </div>
    </div>
</div>
@endsection