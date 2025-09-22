<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userLatitude = -6.223722509862206;
        $userLongitude = 106.64923814895317;

        // Get the search value from the request
        $search = $request->input('search');

        // 1. Start the query and add the distance calculation
        $query = Restaurant::query()
            ->select('*') // Select all existing columns
            ->selectRaw(
                // This is the Haversine formula to calculate distance in kilometers
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$userLatitude, $userLongitude, $userLatitude]
            );

        // 2. Apply the search filter ONLY if a search term exists
        if ($search) {
            // We group the 'where' clauses to ensure correct SQL logic
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('cuisine', 'LIKE', "%{$search}%")
                  ->orWhere('area', 'LIKE', "%{$search}%");
            });
        }
        
        // 3. Add sorting and pagination to the final query
        $restaurants = $query
            ->orderBy('distance', 'asc') // Always sort by the nearest restaurants
            ->paginate(6); // Fetches 6 restaurants per page

        // 4. Return the view with the final data
        return view('welcome', ['restaurants' => $restaurants]);
    }
    
}
