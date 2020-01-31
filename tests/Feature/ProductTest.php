<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * @beforeClass
     */
    public function resetDatabase()
    {
        exec('php artisan db:wipe --env=testing');
        exec('php artisan migrate:refresh --env=testing');
        exec('php artisan db:seed --env=testing');
    }


    /** @test */
    public function it_will_show_all_products()
    {
        $prod = Product::all();

        $response = $this->get(route('product.index'));

        $response->assertStatus(200);

        $response->assertJson($prod->toArray());
    }


    /** @test */
   public function it_will_create_products()
    {
        $response = $this->post(route('product.store'), [
            'id'    => '1',
            'name'  => 'goose',
            'stock' => '12345',
            'price' => '12',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('product', [
            'id'    => '1',
            'name' => 'goose',
            'stock' => '12345',
            'price' => '12',
        ]);

        $response->assertJsonStructure([
            'product' => [
                'name',
                'stock',
                'price',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    /** @test */
    public function it_will_show_a_product()
    {
        $this->post(route('product.store'), [
            'name'       => 'kielbasa'
        ]);

        $product = Product::all()->first();

        $response = $this->get(route('product.show', $product->id));

        $response->assertStatus(200);

        $response->assertJson($product->toArray());
    }

    /** @test */
    public function it_will_update_a_product()
    {
        $this->post(route('product.store'), [
            'name'      => 'burger',
            'stock'     => '121',
            'price'     => '4'

        ]);

        $prod = Product::all()->first();

        $response = $this->put(route('product.update', $prod->id), [
            'name' => 'han burger'
        ]);

        $response->assertStatus(200);

        $prod = $prod->fresh();

        $this->assertEquals($prod->name, 'han burger');

        $response->assertJsonStructure([
           'product' => [
               'title',
               'description',
               'updated_at',
               'created_at',
               'id'
           ]]);
    }

    /** @test */
    public function it_will_delete_a_product()
    {
        //$this->post(route('product.store'), [
        //    'name'  => 'goose',
        //    'stock' => '0',
        //    'price' => '12'
        //]);

        $prod = Product::all()->first();

        $response = $this->delete(route('product.destroy', $prod->id));

        $prod = $prod->fresh();

        $this->assertNull($prod);

        //$response->assertJsonStructure([
        //    'bye bye goose'
        //]);
    }
}
