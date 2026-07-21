<?php

namespace Tests\Feature;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialtyTest extends TestCase
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

    public function test_can_list_specialties()
    {
        Specialty::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/specialties');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'is_active'],
                     ],
                 ]);
    }

    public function test_can_create_specialty()
    {
        $data = [
            'title' => 'Wedding Cakes',
            'excerpt' => 'Beautiful custom cakes',
            'description' => 'We create custom wedding cakes for your special day',
            'is_active' => true,
            'order' => 1,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/specialties', $data);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('specialties', [
            'title' => 'Wedding Cakes',
        ]);
    }

    public function test_can_update_specialty()
    {
        $specialty = Specialty::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/specialties/' . $specialty->id, [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('specialties', [
            'id' => $specialty->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_can_delete_specialty()
    {
        $specialty = Specialty::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/v1/specialties/' . $specialty->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('specialties', ['id' => $specialty->id]);
    }
}














