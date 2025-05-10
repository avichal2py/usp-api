@extends('lecturer.layout')

@section('content')
<div class="grade-container">
  <h2>📘 Select a Course</h2>

  @if (session('success'))
    <div class="success">{{ session('success') }}</div>
  @endif

  <form method="GET" class="course-form">
    @foreach ($courses as $course)
      <button type="submit" name="course_code" value="{{ $course->course_code }}">{{ $course->course_code }}</button>
    @endforeach
  </form>

  @if ($selectedCourse)
    <h3 class="section-heading">Students in {{ $selectedCourse }}</h3>

    <form method="GET" class="search-form">
      <input type="hidden" name="course_code" value="{{ $selectedCourse }}">
      <input type="text" name="search" placeholder="Search by name or ID" value="{{ request('search') }}">
      <button type="submit">Search</button>
    </form>

    @foreach ($students as $student)
      <div class="student-card">
        <p><strong>{{ $student->student_id }}</strong> - {{ $student->first_name }} {{ $student->last_name }}</p>
        <p>Status: {{ $student->status }}</p>

        <form method="POST" action="{{ route('lecturer.submitGrade') }}">
          @csrf
          <input type="hidden" name="track_id" value="{{ $student->id }}">

          <select name="grade" required>
            <option value="">Select grade</option>
            @foreach (['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'E', 'F'] as $g)
              <option value="{{ $g }}">{{ $g }}</option>
            @endforeach
          </select>

          <button type="submit">Submit Grade</button>
        </form>
      </div>
    @endforeach
  @endif
</div>

<style>
  .grade-container {
    padding: 20px;
  }

  h2 {
    font-size: 20px;
    margin-bottom: 12px;
  }

  .course-form button {
    background-color: #29a3a3;
    color: white;
    padding: 8px 16px;
    margin: 4px 6px 10px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .course-form button:hover {
    background-color: #238c8c;
  }

  .section-heading {
    margin-top: 20px;
    font-size: 18px;
  }

  .search-form {
    margin: 10px 0 20px;
  }

  .search-form input[type="text"] {
    padding: 6px;
    width: 250px;
    margin-right: 6px;
  }

  .search-form button {
    padding: 6px 12px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
  }

  .student-card {
    background-color: #f0f0f0;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
  }

  .student-card form {
    margin-top: 8px;
  }

  .student-card select {
    padding: 6px;
    margin-right: 10px;
  }

  .student-card button {
    padding: 6px 12px;
    background-color: #27ae60;
    color: white;
    border: none;
    border-radius: 4px;
  }

  .success {
    color: green;
    margin-bottom: 15px;
  }
</style>
@endsection
