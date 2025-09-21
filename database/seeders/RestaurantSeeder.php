<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use Illuminate\Support\Str; // Import the Str class for generating slugs

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = [
            [
                'name' => 'Geprek Bensu',
                'cuisine' => 'Indonesian',
                'area' => 'BSD',
                'address' => 'Jl. Pahlawan Seribu No.27, Lengkong Gudang, Kec. Serpong, Kota Tangerang Selatan',
                'rating' => 4.5,
                'price_range' => 'Rp. 15.000 - Rp. 50.000',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/en/1/12/Geprek_Bensu_logo.png'
            ],
            [
                'name' => 'Mama Djempol',
                'cuisine' => 'Indonesian',
                'area' => 'Alam Sutera',
                'address' => 'Ruko Alam Sutera, Jl. Jalur Sutera Kav. 29D No. 28, Pakualam, Kec. Serpong Utara',
                'rating' => 4.8,
                'price_range' => 'Rp. 20.000 - Rp. 30.000',
                'image_url' => 'https://i.gojekapi.com/darkroom/gofood-indonesia/v2/images/uploads/bdb3158f-c18c-4db2-9df8-f73b741d4ada.jpg?auto=format'
            ],
            [
                'name' => 'Pizza Hut',
                'cuisine' => 'Italian',
                'area' => 'Gading Serpong',
                'address' => 'Summarecon Mall Serpong, Jl. Gading Serpong Boulevard, Pakulonan Bar., Kec. Klp. Dua',
                'rating' => 4.2,
                'price_range' => 'Rp. 50.000 - Rp. 100.000',
                'image_url' => 'https://www.allrecipes.com/thmb/ZGW3Q8JIrZW9HNlZNC0EuZIZ7Bw=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/PH25050_CheesyBites_C-e0cca56ef7fe4c8f860a3370ab527c5d.jpg'
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create([
                'name' => $restaurant['name'],
                'slug' => Str::slug($restaurant['name'] . '-' . $restaurant['area']),
                'cuisine' => $restaurant['cuisine'],
                'area' => $restaurant['area'],
                'address' => $restaurant['address'],
                'rating' => $restaurant['rating'],
                'price_range' => $restaurant['price_range'],
                'image_url' => $restaurant['image_url'],
            ]);
        }
    }
}