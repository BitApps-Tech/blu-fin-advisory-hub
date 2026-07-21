<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
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

    public function test_can_list_gallery_items()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);
        GalleryItem::factory()->count(3)->create(['image_id' => $media->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/gallery');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'is_active', 'image'],
                     ],
                 ]);
    }

    public function test_can_create_gallery_item()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);
        
        $data = [
            'title' => 'Beautiful Pastry',
            'caption' => 'Our signature croissant',
            'category' => GalleryItem::CATEGORY_EVENTS,
            'image_id' => $media->id,
            'is_active' => true,
            'order' => 1,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/gallery', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('gallery_items', [
            'title' => 'Beautiful Pastry',
            'image_id' => $media->id,
        ]);
    }

    public function test_can_update_gallery_item()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);
        $item = GalleryItem::factory()->create(['image_id' => $media->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/gallery/' . $item->id, [
            'title' => 'Updated Title',
            'is_active' => false,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('gallery_items', [
            'id' => $item->id,
            'title' => 'Updated Title',
            'is_active' => false,
        ]);
    }

    public function test_can_delete_gallery_item()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);
        $item = GalleryItem::factory()->create(['image_id' => $media->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/v1/gallery/' . $item->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('gallery_items', ['id' => $item->id]);
    }

    public function test_public_gallery_returns_only_active_items()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);
        
        GalleryItem::factory()->count(2)->create([
            'image_id' => $media->id,
            'is_active' => true,
        ]);
        
        GalleryItem::factory()->count(3)->create([
            'image_id' => $media->id,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/v1/public/gallery');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_public_gallery_filters_by_category()
    {
        $media = Media::factory()->create(['created_by' => $this->user->id]);

        GalleryItem::factory()->count(2)->create([
            'image_id' => $media->id,
            'category' => GalleryItem::CATEGORY_EVENTS,
            'is_active' => true,
        ]);

        GalleryItem::factory()->count(3)->create([
            'image_id' => $media->id,
            'category' => GalleryItem::CATEGORY_AGRO,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/public/gallery?category=events');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_validation_fails_with_missing_image()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/gallery', [
            'title' => 'Test',
            // Missing image_id
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors']);
    }
}

