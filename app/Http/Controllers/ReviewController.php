<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:500',
            'price_per_portion' => 'required|numeric|min:1000',
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'images.max' => 'Maksimal hanya boleh mengupload 3 foto.',
            'images.*.max' => 'Ukuran setiap foto tidak boleh lebih dari 5MB.'
        ]);

        $existingReview = Review::withTrashed()
            ->where('user_id', Auth::id())
            ->where('restaurant_id', $restaurant->id)
            ->first();

        if ($existingReview && !$existingReview->trashed()) {
            return back()->with('error', 'Anda sudah mereview tempat ini. Silakan edit ulasan lama Anda melalui halaman Riwayat Ulasan.');
        }

        $isRestored = false;

        DB::transaction(function () use ($request, $restaurant, $validated, $existingReview, &$isRestored) {

            if ($existingReview) {
                if ($existingReview->trashed()) {
                    $existingReview->restore();
                    $existingReview->update([
                        'rating' => $validated['rating'],
                        'content' => $validated['content'],
                        'price_per_portion' => $validated['price_per_portion'],
                        'created_at' => now(),
                    ]);
                    foreach ($existingReview->images as $oldImage) {
                        $oldImage->delete();
                    }
                    $review = $existingReview;
                    $isRestored = true;
                }
            } else {
                $review = Review::create([
                    'user_id' => Auth::id(),
                    'restaurant_id' => $restaurant->id,
                    'rating' => $validated['rating'],
                    'content' => $validated['content'],
                    'price_per_portion' => $validated['price_per_portion'],
                ]);
            }

            if (isset($review) && $request->hasFile('images')) {
                foreach ($request->file('images') as $photo) {
                    $result = cloudinary()->uploadApi()->upload($photo->getRealPath(), [
                        'folder' => 'reviews'
                    ]);
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'path' => $result['secure_url']
                    ]);
                }
            }

            $this->recalculateRestaurantStats($restaurant);
        });

        if ($isRestored) {
            return back()->with('success', 'Ulasan lama Anda berhasil diperbarui!');
        }

        return back()->with('success', 'Ulasan dan foto berhasil ditambahkan!');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) abort(403);

        $countExisting = $review->images()->count();
        $deleteImageIds = $request->input('deleted_images', []);
        $countToDelete = is_array($deleteImageIds) ? count(array_filter($deleteImageIds)) : 0;
        $countNew = $request->hasFile('new_images') ? count($request->file('new_images')) : 0;

        $finalImageCount = ($countExisting - $countToDelete) + $countNew;

        if ($finalImageCount > 3) {
            return back()->with('error', 'Total foto tidak boleh lebih dari 3. Silakan hapus beberapa foto lama.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'deleted_images' => ['nullable', 'array'],
            'deleted_images.*' => ['integer', 'exists:review_images,id'],
        ]);

        DB::transaction(function () use ($request, $review, $validated) {
            $history = $review->edit_history ?? [];
            $history[] = [
                'date' => now()->toDateTimeString(),
                'old_content' => $review->content,
                'old_rating' => $review->rating,
            ];

            $review->update([
                'rating' => $validated['rating'],
                'content' => $validated['content'],
                'edited_at' => now(),
                'edit_history' => $history
            ]);

            $deleteIds = $request->input('deleted_images', []);
            if (is_array($deleteIds) && count($deleteIds) > 0) {
                $imagesToDelete = $review->images()->whereIn('id', $deleteIds)->get();
                foreach ($imagesToDelete as $img) {
                    if (Str::startsWith($img->path, 'http')) {
                        $publicId = 'reviews/' . pathinfo($img->path, PATHINFO_FILENAME);
                        cloudinary()->uploadApi()->destroy($publicId);
                    } else {
                        if (Storage::disk('cloudinary')->exists($img->path)) {
                            Storage::disk('cloudinary')->delete($img->path);
                        }
                    }
                    $img->delete();
                }
            }

            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $photo) {
                    $result = cloudinary()->uploadApi()->upload($photo->getRealPath(), [
                        'folder' => 'reviews'
                    ]);
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'path' => $result['secure_url']
                    ]);
                }
            }
            $this->recalculateRestaurantStats($review->restaurant);
        });

        return redirect()->route('history')->with('success', 'Ulasan berhasil diperbarui.');
    }

    private function recalculateRestaurantStats(Restaurant $restaurant)
    {
        $avgRating = $restaurant->reviews()->whereNull('deleted_at')->avg('rating');
        $avgPrice = $restaurant->reviews()->whereNull('deleted_at')->avg('price_per_portion');
        $totalReviews = $restaurant->reviews()->whereNull('deleted_at')->count();

        $restaurant->update([
            'avg_rating' => $avgRating,
            'avg_price' => $avgPrice,
            'total_reviews' => $totalReviews
        ]);
    }

    public function community()
    {
        $reviews = Review::with(['user', 'restaurant', 'images'])
            ->withSum('votes', 'vote_value')
            ->latest()
            ->paginate(10);

        return view('pages.community', compact('reviews'));
    }

    public function history()
    {
        $reviews = Review::with(['restaurant', 'images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.history', compact('reviews'));
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.reviews.edit', compact('review'));
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan Anda berhasil dihapus.');
    }
}
