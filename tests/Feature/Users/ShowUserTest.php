<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    private readonly User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function it_should_be_able_to_get_a_single_user()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('api.users.show', [
                'api_key' => $this->admin->api_key,
                'user'    => $this->admin->id,
            ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('data')
            ->assertSee('name')
            ->assertSee('email');
    }

    /** @test */
    public function it_should_not_be_able_to_get_an_invalid_user()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('api.users.show', [
                'api_key' => $this->admin->api_key,
                'user'    => 10,
            ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function non_admin_should_only_see_their_user()
    {
        $testUser = User::factory()->create();
        $user     = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->getJson(route('api.users.show', [
                'api_key' => $this->admin->api_key,
                'user'    => $user->id,
            ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('data')
            ->assertSee('name')
            ->assertSee('email');

        $response = $this->actingAs($user)
            ->getJson(route('api.users.show', [
                'api_key' => $this->admin->api_key,
                'user'    => $testUser->id,
            ]));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
