<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_login()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'user' => ['token'],
            ]);
    }

    /** @test */
    public function it_should_not_be_able_to_login_with_wrong_credentials()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => 'wrong@email.com',
            'password' => 'password wrong',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /** @test */
    public function email_should_be_required_and_valid()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => null,
            'password' => 'password wrong',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email' => 'required']);

        $response = $this->postJson(route('api.auth.login'), [
            'email'    => 'invalid email',
            'password' => 'password wrong',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email' => 'email']);
    }

    /** @test */
    public function password_should_be_required_and_grather_than_8_characters()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => 'email@email.com',
            'password' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password' => 'required']);

        $response = $this->postJson(route('api.auth.login'), [
            'email'    => 'email@email.com',
            'password' => '123',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }
}
