<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Register Page
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }


    /*
    |--------------------------------------------------------------------------
    | Register User
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100'
            ],

            'username' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:users,username'
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],

        ]);


        $user = User::create([

            'name' => $validated['name'],

            'username' => $validated['username'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'role' => 'user',

            'is_active' => true,

        ]);


        Auth::login($user);


        $request->session()->regenerate();


        return redirect()
            ->route('home')
            ->with(
                'success',
                'Welcome to Tradim!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Login Page
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => [
                'required',
                'email'
            ],

            'password' => [
                'required'
            ],

        ]);


        $remember = $request->boolean('remember');


        if (
            Auth::attempt(
                $credentials,
                $remember
            )
        ) {

            $request->session()->regenerate();


            if (!Auth::user()->is_active) {

                Auth::logout();

                return back()
                    ->withErrors([
                        'email' =>
                            'Your account is currently disabled.'
                    ])
                    ->onlyInput('email');
            }


            return redirect()
                ->intended(
                    route('home')
                )
                ->with(
                    'success',
                    'Welcome back to Tradim!'
                );
        }


        return back()
            ->withErrors([
                'email' =>
                    'The email or password is incorrect.'
            ])
            ->onlyInput('email');
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();


        $request->session()->invalidate();


        $request->session()->regenerateToken();


        return redirect()
            ->route('home')
            ->with(
                'success',
                'You have been logged out.'
            );
    }
}