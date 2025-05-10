@extends('student.layout')

@section('content')
<style>
    .container {
        max-width: 960px;
        margin: 20px auto;
        padding: 1%;
        font-family: 'Segoe UI', sans-serif;
        background: #fff;
        height:90vh;
        overflow-y: auto;
    }

    h3 {
        margin-bottom: 5px;
        color: #333;
    }

    .info-text {
        margin: 2px 0 12px 0;
        color: #555;
    }

    .progress-section {
        margin-bottom: 20px;
    }

    .progress-text {
        font-size: 16px;
        margin-bottom: 6px;
    }

    .progress-bar-bg {
        height: 12px;
        background-color: #e0e0e0;
        border-radius: 6px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: #4caf50;
        transition: width 0.3s ease;
    }

    .legend {
        display: flex;
        gap: 16px;
        margin: 16px 0;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .level-group {
        margin-bottom: 24px;
    }

    .level-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .course-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    .course-table th,
    .course-table td {
        padding: 10px;
        border: 1px solid #ccc;
        font-size: 15px;
        text-align: left;
    }

    .completed {
        background-color: #d4edda;
    }

    .enrolled {
        background-color: #cce5ff;
    }

    .not-started {
        background-color: #f2f2f2;
    }
</style>

<div class="container">
    <h3>Course Visualization</h3>
    <p class="info-text"><strong>Student:</strong> {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</p>
    <p class="info-text"><strong>Program:</strong> {{ $student->program_name ?? 'N/A' }}</p>

    @php
        $completedCount = 0;
        $totalCount = 0;
        foreach ($levels as $level) {
            foreach ($level['courses'] as $course) {
                $totalCount++;
                if ($course['status'] === 'Completed') $completedCount++;
            }
        }
        $percent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
    @endphp

    <div class="progress-section">
        <p class="progress-text">Completed {{ $completedCount }} of {{ $totalCount }} courses ({{ $percent }}%)</p>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
        </div>
    </div>

    <a href="{{ route('student.downloadCompletedCourses') }}">
    <button style="
        background-color: #29a3a3;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 10px;
    ">
        📄 Download Completed Courses Report
    </button>
</a>


    <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #d4edda;"></div>
            <span>(✅) Completed</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #cce5ff;"></div>
            <span>(R) Registered</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #f2f2f2;"></div>
            <span>Not Started</span>
        </div>
    </div>

    @foreach ($levels as $level)
        <div class="level-group">
            <p class="level-title">Level {{ $level['level'] }}</p>
            <table class="course-table">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Grade</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($level['courses'] as $course)
                        <tr class="{{ $course['status'] === 'Completed' ? 'completed' : ($course['status'] === 'Enrolled' ? 'enrolled' : 'not-started') }}">
                            <td>{{ $course['course_code'] }}</td>
                            <td>{{ $course['label'] }}</td>
                            <td>{{ $course['status'] }}</td>
                            <td>{{ $course['grade'] ?? '—' }}</td>
                            <td>{{ $course['semester'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
@endsection
