<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);
        
        $this->user = User::first();
        $this->token = auth('api')->login($this->user);
        
        $this->category = MenuCategory::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);
    }

    public function test_can_list_menu_items()
    {
        MenuItem::factory()->count(3)->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/menu-items');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'price', 'is_active'],
                     ],
                 ]);
    }

    public function test_can_create_menu_item()
    {
        $data = [
            'category_id' => $this->category->id,
            'name' => 'Chocolate Cake',
            'description' => 'Delicious chocolate cake',
            'price' => 25.99,
            'is_special' => true,
            'is_active' => true,
            'order' => 1,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/menu-items', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('menu_items', [
            'name' => 'Chocolate Cake',
            'price' => 25.99,
        ]);
    }

    public function test_can_update_menu_item()
    {
        $item = MenuItem::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/menu-items/' . $item->id, [
            'name' => 'Updated Name',
            'price' => 30.00,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('menu_items', [
            'id' => $item->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_menu_item()
    {
        $item = MenuItem::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/v1/menu-items/' . $item->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('menu_items', ['id' => $item->id]);
    }

    public function test_validation_fails_with_invalid_data()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/menu-items', [
            'name' => '', // Empty name
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors']);
    }
}














