<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ToggleStudentRestrictionTest extends TestCase
{
    public function test_toggle_restriction_for_existing_student()
    {
        // Mock student exists
        $student = (object)[
            'student_id' => 'S12345',
            'is_restricted' => false,
        ];

        // Mock DB SELECT
        DB::shouldReceive('table')->with('active_students')->once()->andReturnSelf();
        DB::shouldReceive('where')->with('student_id', 'S12345')->once()->andReturnSelf();
        DB::shouldReceive('first')->once()->andReturn($student);

        // Mock DB UPDATE
        DB::shouldReceive('table')->with('active_students')->once()->andReturnSelf();
        DB::shouldReceive('where')->with('student_id', 'S12345')->once()->andReturnSelf();
        DB::shouldReceive('update')->with(['is_restricted' => true])->once()->andReturn(true);

        // Call POST route
        $response = $this->post(route('admin.restrict.toggle', ['id' => 'S12345']));

        // Assert redirect with success
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Restriction status updated.');
    }

    public function test_toggle_restriction_for_nonexistent_student()
    {
        // Mock student not found
        DB::shouldReceive('table')->with('active_students')->once()->andReturnSelf();
        DB::shouldReceive('where')->with('student_id', 'INVALID')->once()->andReturnSelf();
        DB::shouldReceive('first')->once()->andReturn(null);

        // Call POST route
        $response = $this->post(route('admin.restrict.toggle', ['id' => 'INVALID']));

        // Assert redirect with error
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Student not found.');
    }
}
