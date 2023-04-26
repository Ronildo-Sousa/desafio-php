<?php

namespace Tests\Feature\Products;

use App\Models\{Product, User};
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Product::factory(10)->create();
    }

    /** @test */
    public function it_should_show_a_list_of_products()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('api.products.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['products'])
            ->assertSee('Next')
            ->assertSee('Previous');

        $this->assertNotEmpty($response->collect()->get('products')["per_page"]);
    }

    /** @test  */
    public function it_gets_per_page_parm_from_url()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('api.products.index', [
                'per-page' => 2,
            ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['products']);

        $this->assertEquals(
            2,
            $response->collect()->get('products')["per_page"]
        );
    }
}
