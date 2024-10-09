<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Error during login: ' . $e->getMessage());
        }


        $user = User::firstOrCreate([
            'email' => $socialUser->getEmail()
        ], [
            'name' => $socialUser->getName(),
            'provider_id' => $socialUser->getId(),
            'provider' => $provider,
            'password' => Hash::make(Str::random(10)),
        ]);

        Auth::login($user);

        return redirect()->route('game.index');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

}
