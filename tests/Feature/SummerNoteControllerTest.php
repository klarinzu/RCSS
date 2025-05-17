<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SummerNoteControllerTest extends ControllerTestCase
{
    public function test_admin_can_upload_image()
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('admin.summernote.upload'), [
            'file' => $file
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['url']);
        Storage::disk('public')->assertExists('summernote/' . $file->hashName());
    }

    public function test_admin_cannot_upload_invalid_file()
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->post(route('admin.summernote.upload'), [
            'file' => $file
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }

    public function test_admin_cannot_upload_file_without_file()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.summernote.upload'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }

    public function test_guest_cannot_upload_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('admin.summernote.upload'), [
            'file' => $file
        ]);

        $response->assertStatus(401);
    }
} 