<?php

namespace Tests\Feature\Products;

use App\Enums\ProductStatus;
use App\Models\{Product, User};
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    /** @test */
    public function it_should_change_the_product_status_to_trash()
    {
        $user    = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('api.products.destroy', [
                'product' => $product->code,
            ]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('products', ['status' => ProductStatus::published->value]);
    }

    /** @test */
    public function an_invalid_product_code_should_return_a_not_found_error()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->deleteJson(route('api.products.destroy', [
                'product' => 0,
            ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function only_admin_can_delete_a_product()
    {
        $user    = User::factory()->create(['is_admin' => false]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('api.products.destroy', [
                'product' => $product->code,
            ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
