@extends('student.layout')

@section('content')
  <style>
    h1 {
      font-size: 28px;
      margin-bottom: 15px;
      color: #333;
    }

    .message {
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    .message.success {
      background-color: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }

    .message.error {
      background-color: #ffebee;
      color: #c62828;
      border: 1px solid #ef9a9a;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 14px;
    }

    th {
      background-color: #f1f1f1;
      font-weight: bold;
      color: #333;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    button {
      padding: 8px 14px;
      background-color: #29a3a3;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
    }

    button:hover {
      background-color: #238c8c;
    }

    .status {
      font-weight: bold;
    }

    .status.completed {
      color: #388e3c;
    }

    .status.enrolled {
      color: #1976d2;
    }

    .no-courses {
      color: #555;
      font-style: italic;
    }
  </style>

<div style="height: 100vh; overflow-y: auto; padding-right: 10px; box-sizing: border-box;">
  <h1>Courses</h1>

  @if(session('success'))
    <p style="color: green; margin-bottom: 10px;">{{ session('success') }}</p>
  @endif

  @if(session('error'))
    <p style="color: red; margin-bottom: 10px;">{{ session('error') }}</p>
  @endif

  @if(!empty($courses) && count($courses) === 0)
    <p>No courses available for your program.</p>
  @elseif(!empty($courses))
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr>
          <th style="border: 1px solid #ccc; padding: 8px;">Code</th>
          <th style="border: 1px solid #ccc; padding: 8px;">Name</th>
          <th style="border: 1px solid #ccc; padding: 8px;">Semester</th>
          <th style="border: 1px solid #ccc; padding: 8px;">Status</th>
          <th style="border: 1px solid #ccc; padding: 8px;">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($courses as $course)
          <tr>
            <td style="border: 1px solid #ccc; padding: 8px;">{{ $course['course_code'] }}</td>
            <td style="border: 1px solid #ccc; padding: 8px;">{{ $course['course_name'] }}</td>
            <td style="border: 1px solid #ccc; padding: 8px;">{{ $course['semester'] }}</td>
            <td style="border: 1px solid #ccc; padding: 8px;">
              @if($course['status'] === 'Completed')
                ✅ Completed
              @elseif($course['status'] === 'Enrolled')
                (R) Enrolled
              @else
                -
              @endif

              {{-- Optional prerequisites --}}
              @if(!empty($prerequisites[$course['course_code']]))
                <div style="margin-top: 5px;">
                  <strong>Prerequisites:</strong>
                  <ul style="margin: 5px 0 0 15px; padding: 0;">
                    @foreach($prerequisites[$course['course_code']] as $pre)
                      <li>{{ $pre }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
            </td>
            <td style="border: 1px solid #ccc; padding: 8px;">
              @if($course['status'] !== 'Completed' && $course['semester'] == $current_semester)
                <form method="POST" action="{{ route('student.register-course') }}">
                  @csrf
                  <input type="hidden" name="student_id" value="{{ $student_id }}">
                  <input type="hidden" name="course_code" value="{{ $course['course_code'] }}">
                  <button type="submit">
                    {{ $course['status'] === 'Enrolled' ? 'Unregister' : 'Register' }}
                  </button>
                </form>
              @elseif($course['status'] !== 'Completed')
                <p style="color: red; margin-top: 5px;">This course is not available this semester.</p>
              @else
                -
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>Courses could not be loaded.</p>
  @endif
</div>
@endsection
