<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('auth')
            ->get('/__testing/protected-resource', function () {
                return response()->json([
                    'message' => 'Authenticated',
                ]);
            });
    }

    public function test_guest_cannot_access_protected_resource(): void
    {
        $response = $this->getJson(
            '/__testing/protected-resource'
        );

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_protected_resource(): void
    {
        $user = User::factory()->make();

        $response = $this
            ->actingAs($user)
            ->getJson('/__testing/protected-resource');

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Authenticated',
            ]);
    }
}