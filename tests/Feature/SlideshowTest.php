<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Content;
use App\Models\User;
use Tests\TestCase;

class SlideshowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::create(
            [
                'name'               => 'Test User',
                'email'              => 'test@example.com',
                'password'           => bcrypt('password'),
                'birth_date'         => '2000-01-01',
                'gender'             => 'm',
                'preferred_language' => 'en',
                'external_user'      => 0,
            ]
        );
        $this->actingAs($user);
    }

    public function testRecursivePhotoFetching()
    {
        $parentAlbum = Album::create(['name' => 'Parent Album']);
        $childAlbum  = Album::create(['name' => 'Child Album', 'parent_id' => $parentAlbum->id]);

        Content::create(['name' => 'Photo 1', 'parent_id' => $parentAlbum->id]);
        Content::create(['name' => 'Photo 2', 'parent_id' => $childAlbum->id]);

        $this->assertEquals(2, $parentAlbum->fresh()->getAllContents()->count());
        $this->assertTrue($parentAlbum->fresh()->hasContentRecursive());

        $this->assertEquals(1, $childAlbum->fresh()->getAllContents()->count());
        $this->assertTrue($childAlbum->fresh()->hasContentRecursive());
    }

    public function testAllPhotosEndpoint()
    {
        $parentAlbum = Album::create(['name' => 'Parent Album']);
        $childAlbum  = Album::create(['name' => 'Child Album', 'parent_id' => $parentAlbum->id]);

        // We need to handle media for URL generation if we want to test URLs,
        // but for now let's just test if the endpoint returns the correct count.
        Content::create(['name' => 'Photo 1', 'parent_id' => $parentAlbum->id]);
        Content::create(['name' => 'Photo 2', 'parent_id' => $childAlbum->id]);

        $response = $this->get(route('album.photos', ['album' => $parentAlbum->id]));
        $response->assertStatus(200);
        $response->assertJsonCount(2);

        $response = $this->get(route('album.photos', ['album' => $childAlbum->id]));
        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    public function testAllPhotosEndpointNoAlbum()
    {
        $album = Album::create(['name' => 'Album 1']);
        Content::create(['name' => 'Photo 1', 'parent_id' => $album->id]);
        Content::create(['name' => 'Photo 2', 'parent_id' => $album->id]);

        $response = $this->get(route('photos.all'));
        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }
}
