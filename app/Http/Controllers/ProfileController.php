<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('pages.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $user = $request->user();

            if ($user->profile_picture) {
                if (Str::startsWith($user->profile_picture, 'http')) {
                    $publicId = 'profile-pictures/' . pathinfo($user->profile_picture, PATHINFO_FILENAME);
                    Cloudinary::uploadApi()->destroy($publicId);
                } else {
                    Storage::disk('cloudinary')->delete($user->profile_picture);
                }
            }

            $result = Cloudinary::uploadApi()->upload($request->file('profile_picture')->getRealPath(), [
                'folder' => 'profile-pictures'
            ]);

            $validatedData['profile_picture'] = $result['secure_url'];
        }

        $request->user()->fill($validatedData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->profile_picture) {
            if (Str::startsWith($user->profile_picture, 'http')) {
                $publicId = 'profile-pictures/' . pathinfo($user->profile_picture, PATHINFO_FILENAME);

                Cloudinary::uploadApi()->destroy($publicId);
            } else {
                Storage::disk('cloudinary')->delete($user->profile_picture);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
