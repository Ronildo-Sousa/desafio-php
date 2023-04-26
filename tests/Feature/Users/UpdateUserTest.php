<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    private readonly User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function it_should_be_able_to_admin_update_a_single_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->putJson(route('api.users.update', [
                'user'     => $user->id,
                'name'     => 'updated name',
                'is_admin' => true,
            ]));

        $updatedUser = User::query()->where('email', $user->email)->first(['name', 'is_admin']);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals('updated name', $updatedUser->name);
        $this->assertEquals(true, $updatedUser->is_admin);
    }

    /** @test */
    public function it_should_not_be_able_to_update_an_invalid_user()
    {
        $response = $this->actingAs($this->admin)
            ->putJson(route('api.users.update', [
                'user' => 10,
            ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function non_admin_should_not_update_is_admin_field()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->putJson(route('api.users.update', [
                'user'     => $user->id,
                'name'     => 'my name',
                'is_admin' => true,
            ]));

        $updatedUser = User::query()->where('email', $user->email)->first(['name', 'is_admin']);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals('my name', $updatedUser->name);
        $this->assertEquals(false, $updatedUser->is_admin);
    }
}
