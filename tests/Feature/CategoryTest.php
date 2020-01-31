<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

     /**
     * @beforeClass
     */
    public static function resetDatabase()
    {
        exec('php artisan db:wipe --env=testing');
        exec('php artisan migrate:refresh --env=testing');
        exec('php artisan db:seed --env=testing');
    }

    /** @test */
    public function it_will_show_all_categories()
    {
        //$cat = factory(Category::class, 10)->create();
        $cat = Category::all();

        $response = $this->get(route('category.index'));

        $response->assertStatus(200);

        $response->assertJson($cat->toArray());
    }

    /** @test */
    public function it_will_create_categories()
    {
        $response = $this->post(route('category.store'), [
            'name'     => 'jean pierre'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'name' => 'jean pierre'
        ]);

        $response->assertJsonStructure([
            'category' => [
                'name',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }
    
    /** @test */
    public function it_will_show_a_category()
    {
        $this->post(route('category.store'), [
            'name'       => 'banane'
        ]);

        $cat = Category::all()->first();

        $response = $this->get(route('category.show', $cat->id));

        $response->assertStatus(200);

        $response->assertJson($cat->toArray());
    }

    /** @test */
    public function it_will_update_a_category()
    {
        $this->post(route('category.store'), [
            'name'       => 'old categorie'
        ]);

        $cat = Category::all()->first();

        $response = $this->put(route('category.update', $cat->id), [
            'name' => 'nouvelle categorie'
        ]);

        $response->assertStatus(200);

        $cat = $cat->fresh();

        $this->assertEquals($cat->name, 'nouvelle categorie');

        //$response->assertJsonStructure(['name']);
    }

    /** @test */
    public function it_will_delete_a_category()
    {

        $cat = Category::all()->first();

        $response = $this->delete(route('category.destroy', $cat->id));

        $cat = $cat->fresh();

        $this->assertNull($cat);

        //$response->assertJsonStructure(['La banane n est plus.']);
    }
}
