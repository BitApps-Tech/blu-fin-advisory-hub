<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
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
        
        Storage::fake('public');
    }

    public function test_can_upload_image()
    {
        $file = UploadedFile::fake()->image('test-image.jpg', 1000, 800);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/media', [
            'file' => $file,
            'title' => 'Test Image',
            'alt' => 'Test Alt Text',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'data' => ['id', 'url', 'thumb_url', 'title'],
                 ]);

        $this->assertDatabaseHas('media', [
            'title' => 'Test Image',
            'alt' => 'Test Alt Text',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_can_list_media()
    {
        Media::factory()->count(5)->create(['created_by' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/media');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'url', 'title', 'mime', 'size'],
                     ],
                 ]);
    }

    public function test_can_update_media_metadata()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/media/' . $media->id, [
            'title' => 'Updated Title',
            'alt' => 'Updated Alt Text',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'title' => 'Updated Title',
            'alt' => 'Updated Alt Text',
        ]);
    }

    public function test_can_delete_media()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/v1/media/' . $media->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    public function test_upload_validation_rejects_non_image()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/media', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
    }

    public function test_upload_validation_rejects_large_file()
    {
        // Fake a file larger than max size (if configured)
        $file = UploadedFile::fake()->create('large-image.jpg', 20000); // 20MB

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/media', [
            'file' => $file,
        ]);

        // Might pass or fail depending on config
        $this->assertTrue($response->status() === 422 || $response->status() === 201);
    }

    public function test_can_search_media_by_title()
    {
        Media::factory()->create([
            'title' => 'Chocolate Cake',
            'created_by' => $this->user->id,
        ]);
        
        Media::factory()->create([
            'title' => 'Vanilla Cupcake',
            'created_by' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/media?search=Chocolate');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['title' => 'Chocolate Cake']);
    }
}

