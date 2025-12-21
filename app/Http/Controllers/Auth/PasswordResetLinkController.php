<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('breeze.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'phone_number' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)
            ->where('phone_number', $request->phone_number)
            ->first();

        if (! $user) {
            return back()->withInput($request->only('email', 'phone_number'))
                ->withErrors(['email' => 'Email atau Nomor Telepon tidak cocok.']);
        }

        $token = Password::getRepository()->create($user);

        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $user->email
        ]);
    }
}
