<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View { return view('admin.login'); }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) throw ValidationException::withMessages(['email' => 'ایمیل یا رمز عبور صحیح نیست.']);
        $request->session()->regenerate();
        if (!$request->user()?->can('admin.access')) {
            Auth::logout();
            throw ValidationException::withMessages(['email' => 'این حساب دسترسی مدیریت ندارد.']);
        }
        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
