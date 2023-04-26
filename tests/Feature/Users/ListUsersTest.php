<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function PHPUnit\Framework\assertEquals;

use Symfony\Component\HttpFoundation\Response;

use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use LazilyRefreshDatabase;

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

    /** @test */
    public function it_should_be_able_to_change_items_per_page()
    {
        User::factory(10)->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('api.users.index', [
                'api_key'  => $this->admin->api_key,
                'per_page' => 5,
            ]));
        $data       = $response->collect();
        $per_page   = $data->get('meta')['per_page'];
        $itemsCount = count($data->get('data'));

        $response->assertStatus(Response::HTTP_OK);

        assertEquals(5, $per_page);
        assertEquals(5, $itemsCount);
    }
}
