<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class createUserTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_an_account()
    {
        $response = $this->postJson(route('api.auth.register', [
            'name'     => 'user name',
            'email'    => 'user@email.com',
            'password' => 'password',
        ]));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'user' => ['token'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'user@email.com',
        ]);
    }

    /** @test */
    public function it_should_be_able_to_admin_users_create_an_account()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->postJson(route('api.auth.register-admin', [
                'name'     => 'user name',
                'email'    => 'user@email.com',
                'password' => 'password',
            ]));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'user' => ['token'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'user@email.com',
        ]);
    }

    public function test_fields_should_be_required_and_valid()
    {
        $response = $this->postJson(route('api.auth.register', [
            'name'     => '',
            'email'    => '',
            'password' => '',
        ]));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('api.auth.register', [
            'name'     => 'some name',
            'email'    => 'invalid-email',
            'password' => 'password',
        ]));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
