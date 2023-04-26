<?php

namespace Tests\Feature\Products;

use App\Models\{Product, User};
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowProductTest extends TestCase
{
    /** @test */
    public function it_shows_the_product_information_based_in_a_product_code()
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('api.products.show', [
                'product' => $product->code,
            ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['product'])
            ->assertSee($product->code)
            ->assertSee($product->status)
            ->assertSee($product->name);
    }

    /** @test */
    public function test_an_invalid_product_code_should_return_a_not_found_error()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('api.products.show', [
                'product' => 0,
            ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
