<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Authenticate
        |--------------------------------------------------------------------------
        */

        $request->authenticate();

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Redirect to Intended URL
        |--------------------------------------------------------------------------
        |
        | Jika user sebelumnya mencoba mengakses halaman tertentu
        | sebelum login, Laravel akan mengembalikannya ke halaman tersebut.
        |
        | Contoh:
        | /signals/102
        |      ↓
        | /login
        |      ↓
        | login berhasil
        |      ↓
        | /signals/102
        |
        | Jika tidak ada intended URL, fallback ke dashboard.
        |
        */

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
