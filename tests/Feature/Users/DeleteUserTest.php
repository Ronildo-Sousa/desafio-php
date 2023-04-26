<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use LazilyRefreshDatabase;

    private readonly User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function it_should_be_able_to_admin_delete_a_single_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('api.users.destroy', [
                'user'    => $user->id,
            ]));

        // $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('users', ['email' => $user->email]);
    }

    /** @test */
    // public function it_should_not_be_able_to_delete_an_invalid_user()
    // {
    //     $response = $this->actingAs($this->admin)
    //         ->getJson(route('api.users.destroy', [
    //             'user'    => 10,
    //         ]));

    //     $response->assertStatus(Response::HTTP_NOT_FOUND);
    // }

    // /** @test */
    // public function non_admin_should_only_delete_their_account()
    // {
    //     $testUser = User::factory()->create();
    //     $user     = User::factory()->create(['is_admin' => false]);

    //     $response = $this->actingAs($user)
    //         ->getJson(route('api.users.destroy', [
    //             'user'    => $user->id,
    //         ]));
    //     $response->assertStatus(Response::HTTP_NO_CONTENT);

    //     $response = $this->actingAs($user)
    //         ->getJson(route('api.users.destroy', [
    //             'user'    => $testUser->id,
    //         ]));
    //     $response->assertStatus(Response::HTTP_FORBIDDEN);
    // }
}
