<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    private readonly User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function it_should_return_a_list_of_users()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('api.users.index', [
                'api_key' => $this->admin->api_key,
            ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Next')
            ->assertSee('Previous');
    }

    /** @test */
    public function only_admin_should_see_a_list_of_users()
    {
        $nonAdmin = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($nonAdmin)
            ->getJson(route('api.users.index', [
                'api_key' => $nonAdmin->api_key,
            ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
