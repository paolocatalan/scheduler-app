<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function create() {
        return view('auth.register');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email:strict|unique:users',
            'password' => ['required', Password::min(6), 'confirmed']
            ], [
            'name.required' => 'Name Required! Let\'s not be strangers!',
            'email.required' => 'We definitely need your email address!',
            'email.email' => "Hmm, that doesn't look like a valid email.",
        ]);

        $user = User::create($validated);

        event(new Registered($user));

        Auth::login($user);

        return view('auth.verify-email');
    }
}
