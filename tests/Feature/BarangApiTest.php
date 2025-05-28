<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BarangApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test kategori
        $this->kategori = Kategori::create(['nama' => 'Test Kategori']);
        
        // Fake storage
        Storage::fake('public');
    }

    /** @test */
    public function can_get_all_barang()
    {
        // Create test barang
        $barang = Barang::create([
            'nama' => 'Test Barang',
            'jumlah_barang' => 5,
            'id_kategori' => $this->kategori->id,
            'foto' => 'barangs/test.jpg'
        ]);

        $response = $this->getJson('/api/barang');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'nama',
                            'jumlah_barang',
                            'id_kategori',
                            'foto',
                            'foto_url',
                            'kategori'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function can_get_single_barang()
    {
        $barang = Barang::create([
            'nama' => 'Test Barang',
            'jumlah_barang' => 5,
            'id_kategori' => $this->kategori->id,
            'foto' => 'barangs/test.jpg'
        ]);

        $response = $this->getJson("/api/barang/{$barang->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'nama',
                        'jumlah_barang',
                        'id_kategori',
                        'foto',
                        'foto_url',
                        'kategori'
                    ]
                ]);
    }

    /** @test */
    public function can_create_barang_with_photo()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($this->user, 'sanctum')
                        ->postJson('/api/barang', [
                            'nama' => 'New Barang',
                            'jumlah_barang' => 3,
                            'id_kategori' => $this->kategori->id,
                            'foto' => $file
                        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'nama',
                        'jumlah_barang',
                        'id_kategori',
                        'foto',
                        'foto_url',
                        'kategori'
                    ]
                ]);

        $this->assertDatabaseHas('barangs', [
            'nama' => 'New Barang',
            'jumlah_barang' => 3,
            'id_kategori' => $this->kategori->id
        ]);
    }

    /** @test */
    public function can_create_barang_without_photo()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                        ->postJson('/api/barang', [
                            'nama' => 'New Barang No Photo',
                            'jumlah_barang' => 2,
                            'id_kategori' => $this->kategori->id
                        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('barangs', [
            'nama' => 'New Barang No Photo',
            'jumlah_barang' => 2,
            'id_kategori' => $this->kategori->id,
            'foto' => null
        ]);
    }

    /** @test */
    public function can_update_barang()
    {
        $barang = Barang::create([
            'nama' => 'Old Barang',
            'jumlah_barang' => 5,
            'id_kategori' => $this->kategori->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                        ->putJson("/api/barang/{$barang->id}", [
                            'nama' => 'Updated Barang',
                            'jumlah_barang' => 10,
                            'id_kategori' => $this->kategori->id
                        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'nama' => 'Updated Barang',
            'jumlah_barang' => 10
        ]);
    }

    /** @test */
    public function can_delete_barang()
    {
        $barang = Barang::create([
            'nama' => 'To Delete',
            'jumlah_barang' => 1,
            'id_kategori' => $this->kategori->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                        ->deleteJson("/api/barang/{$barang->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('barangs', [
            'id' => $barang->id
        ]);
    }

    /** @test */
    public function requires_authentication_for_create()
    {
        $response = $this->postJson('/api/barang', [
            'nama' => 'Test',
            'jumlah_barang' => 1,
            'id_kategori' => $this->kategori->id
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function validates_required_fields()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                        ->postJson('/api/barang', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nama', 'jumlah_barang', 'id_kategori']);
    }
}
