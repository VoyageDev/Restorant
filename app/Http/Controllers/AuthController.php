<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if ($user && $user->status === 'inactive') {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Status akun tidak aktif.',
                ], 403);
            }

            return back()->withErrors([
                'email' => 'Status akun tidak aktif.',
            ])->onlyInput('email');
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Hanya regenerate session jika bukan API request
            if (! $request->wantsJson()) {
                $request->session()->regenerate();
            }

            if ($request->wantsJson()) {
                /** @var \App\Models\User $authUser */
                $authUser = Auth::user();
                $token = $authUser->createToken('api-token')->plainTextToken;

                return response()->json([
                    'message' => 'Login berhasil.',
                    'token' => $token,
                    'user' => $authUser,
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Email atau password tidak valid.',
            ], 422);
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            $request->user()?->currentAccessToken()?->delete();

            return response()->json([
                'message' => 'Logout berhasil.',
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
