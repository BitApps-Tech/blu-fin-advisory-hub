<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);
        
        $this->user = User::first();
        $this->token = auth('api')->login($this->user);
    }

    public function test_can_create_order()
    {
        $data = [
            'customer_name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'order_type' => 'pickup',
            'items' => [
                [
                    'name' => 'Chocolate Cake',
                    'qty' => 2,
                    'unit_price' => 25.99,
                ],
            ],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/orders', $data);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
        ]);
    }

    public function test_order_total_is_calculated_automatically()
    {
        $data = [
            'customer_name' => 'John Doe',
            'phone' => '1234567890',
            'order_type' => 'pickup',
            'items' => [
                ['name' => 'Item 1', 'qty' => 2, 'unit_price' => 10.00],
                ['name' => 'Item 2', 'qty' => 1, 'unit_price' => 5.00],
            ],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/orders', $data);

        $response->assertStatus(201);

        $order = Order::first();
        $this->assertEquals(25.00, $order->total);
    }

    public function test_can_update_order_status()
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/orders/' . $order->id . '/status', [
            'status' => 'confirmed',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_can_get_order_by_code()
    {
        $order = Order::factory()->create();

        $response = $this->getJson('/api/v1/public/orders/' . $order->code);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'code',
                     'customer_name',
                 ]);
    }

    public function test_order_export_returns_csv()
    {
        Order::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/v1/orders/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}














