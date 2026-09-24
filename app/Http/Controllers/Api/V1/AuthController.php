<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'username' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name' =>
                $validated['name'],

            'username' =>
                $validated['username'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'role' =>
                'user',

            'is_active' =>
                true,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Sanctum Token
        |--------------------------------------------------------------------------
        */

        $token = $user->createToken(
            'tradim-api'
        )->plainTextToken;


        return response()->json([

            'success' => true,

            'message' =>
                'Registration successful.',

            'data' => [

                'user' => $user,

                'token' => $token,

                'token_type' =>
                    'Bearer',

            ],

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $validated['email']
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Invalid Credentials
        |--------------------------------------------------------------------------
        */

        if (
            !$user ||
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {

            throw ValidationException::withMessages([

                'email' =>
                    [
                        'The email or password is incorrect.'
                    ],

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Account Status
        |--------------------------------------------------------------------------
        */

        if (!$user->is_active) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Your account is currently disabled.',

            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Token
        |--------------------------------------------------------------------------
        */
        $token = $user->createToken(
            'tradim-api',
            ['*'],
            now()->addDays(30)
        )->plainTextToken;

        return response()->json([

            'success' => true,

            'message' =>
                'Login successful.',

            'data' => [

                'user' => $user,

                'token' => $token,

                'token_type' =>
                    'Bearer',

            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    public function me(
        Request $request
    ): JsonResponse {

        return response()->json([

            'success' => true,

            'data' => [

                'user' =>
                    $request->user(),

            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ): JsonResponse {

        $token = $request
            ->user()
            ->currentAccessToken();


        if ($token) {
            $token->delete();
        }


        return response()->json([

            'success' => true,

            'message' =>
                'Logged out successfully.',

        ]);
    }


    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all API devices.',
        ]);
    }
}