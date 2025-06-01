<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentRequestTest extends TestCase
{
    public function test_submit_request_form_with_mocked_data()
    {
        // Fake session user
        $student = (object)['student_id' => 'S12345'];

        // Fake storage
        Storage::fake('public');

        // Mock file
        $file = UploadedFile::fake()->create('document.pdf', 5120, 'application/pdf');

        // Mock DB insert
        DB::shouldReceive('table')
            ->once()
            ->with('student_requests')
            ->andReturnSelf();

        DB::shouldReceive('insert')
            ->once()
            ->andReturn(true);

        // Call the route with session and file
        $response = $this->withSession(['user' => $student])->post('/student/request-form', [
            'request_type' => 'Graduation',
            'document' => $file,
        ]);

        // Check redirect and flash message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Request submitted.');

        // Check file was stored
        Storage::disk('public')->assertExists('student_requests');
    }


    public function testFail_submit_request_form_with_mocked_data()
    {
        // Fake session user
        $student = (object)['student_id' => 'S12345'];

        // Fake storage
        Storage::fake('public');

        // Mock file
        $file = UploadedFile::fake()->create('document.pdf', 5121, 'application/pdf');

        // Mock DB insert
        DB::shouldReceive('table')
            ->once()
            ->with('student_requests')
            ->andReturnSelf();

        DB::shouldReceive('insert')
            ->once()
            ->andReturn(true);

        // Call the route with session and file
        $response = $this->withSession(['user' => $student])->post('/student/request-form', [
            'request_type' => 'Graduation',
            'document' => $file,
        ]);

        // Check redirect and flash message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Request submitted.');

        // Check file was stored
        Storage::disk('public')->assertExists('student_requests');
    }


    public function test_submit_request_with_invalid_file_type()
{
    $student = (object)['student_id' => 'S12345'];
    Storage::fake('public');

    $file = UploadedFile::fake()->create('document.exe', 1000, 'application/x-msdownload');

    $response = $this->withSession(['user' => $student])->post('/student/request-form', [
        'request_type' => 'Graduation',
        'document' => $file,
    ]);

    $response->assertSessionHasErrors(['document']);
}



}
