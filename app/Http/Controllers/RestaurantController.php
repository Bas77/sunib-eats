<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request, RestaurantService $restaurantService)
    {
        $hotRestaurants = $restaurantService->getHotRestaurants(4);
        // Disini hot restaurant sudah diambil dari Service/RestaurantService.php

        $restaurants = Restaurant::query()
            ->search($request->input('search'))
            ->filterPrice($request->input('price'))
            ->filterByCategories($request->input('categories'))
            ->sortBy($request->input('sort'))
            ->paginate(8)
            ->withQueryString();
        // Semua logic disini diambil dari Business Logic di Model/Restaurant.php

        $promotions = Promotion::with('restaurant')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        $categories = Category::where('type', '!=', 'price_range')
            ->get()
            ->groupBy('type');

        return view('pages.dashboard', [
            'restaurants' => $restaurants,
            'hotRestaurants' => $hotRestaurants,
            'promotions' => $promotions,
            'categories' => $categories
        ]);
    }

    public function show(Restaurant $restaurant, Request $request)
    {
        $sort = $request->query('sort', 'popular');
        // Default sorting komentar adalah popular jika tidak ada request khusus

        $restaurant->load([
            'promotions' => function ($query) {
                $query->active();
            },
            'reviews' => function ($query) use ($sort) {
                $query->with(['user', 'images'])
                    ->withSum('votes', 'vote_value'); // Hitung total vote

                // Logic sorting komentar (Popular/Recent/Oldest)
                if ($sort === 'recent') {
                    $query->latest();
                } elseif ($sort === 'oldest') {
                    $query->oldest();
                } else {
                    $query->orderByDesc('votes_sum_vote_value')
                        ->latest();
                }
            }
        ]);

        return view('components.restaurant-detail', compact('restaurant'));
    }

    public function explore(Request $request)
    {
        $categories = Category::where('type', '!=', 'price_range')
            ->get()
            ->groupBy('type');
        // Data kategori untuk display di sidebar filter

        $restaurants = Restaurant::query()
            ->search($request->input('search'))
            ->filterPrice($request->input('price'))
            ->filterByCategories($request->input('categories'))
            ->sortBy($request->input('sort'))
            ->paginate(15)
            ->withQueryString();

        return view('pages.explore', [
            'restaurants' => $restaurants,
            'categories' => $categories
        ]);
    }
}
