<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
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

    public function test_can_submit_contact_message()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'subject' => 'Inquiry',
            'message' => 'This is a test message with enough characters.',
        ];

        $response = $this->postJson('/api/v1/public/contact', $data);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
            'is_read' => false,
        ]);
    }

    public function test_can_list_messages()
    {
        ContactMessage::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/messages');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email', 'message', 'is_read'],
                     ],
                 ]);
    }

    public function test_can_mark_message_as_read()
    {
        $message = ContactMessage::factory()->create(['is_read' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/messages/' . $message->id . '/read');

        $response->assertStatus(200);
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'is_read' => true,
        ]);
    }

    public function test_validation_fails_with_short_message()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Short', // Too short
        ];

        $response = $this->postJson('/api/v1/public/contact', $data);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors']);
    }
}














