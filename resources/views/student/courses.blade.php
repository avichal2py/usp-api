@extends('student.layout')

@section('content')
<div class="courses-container">
  <div class="courses-header">
    <h1><i class="fas fa-book"></i> My Courses</h1>
    <div class="semester-info">
      Current Semester: <strong>{{ $current_semester }}</strong>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
  @endif

  @if(!empty($courses) && count($courses) === 0)
    <div class="empty-state">
      <i class="fas fa-book-open"></i>
      <p>No courses available for your program.</p>
    </div>
  @elseif(!empty($courses))
    <div class="courses-table-container">
      <table class="courses-table">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Semester</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($courses as $course)
            <tr class="{{ $course['status'] === 'Enrolled' ? 'enrolled-course' : '' }}">
              <td>{{ $course['course_code'] }}</td>
              <td>{{ $course['course_name'] }}</td>
              <td>{{ $course['semester'] }}</td>
              <td>
                <span class="status-badge {{ strtolower($course['status']) }}">
                  @if($course['status'] === 'Completed')
                    <i class="fas fa-check-circle"></i> Completed
                  @elseif($course['status'] === 'Enrolled')
                    <i class="fas fa-user-graduate"></i> Enrolled
                  @else
                    <i class="fas fa-clock"></i> Available
                  @endif
                </span>

                @if(!empty($prerequisites[$course['course_code']]))
                  <div class="prerequisites">
                    <div class="prereq-toggle" onclick="togglePrereq(this)">
                      <i class="fas fa-chevron-down"></i>
                      <span>Prerequisites</span>
                    </div>
                    <ul class="prereq-list">
                      @foreach($prerequisites[$course['course_code']] as $pre)
                        <li>{{ $pre }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              </td>
              <td>
                @if($course['status'] !== 'Completed' && $course['semester'] == $current_semester)
                  <form method="POST" action="{{ route('student.register-course') }}">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student_id }}">
                    <input type="hidden" name="course_code" value="{{ $course['course_code'] }}">
                    <button type="submit" class="btn {{ $course['status'] === 'Enrolled' ? 'btn-danger' : 'btn-primary' }}">
                      <i class="fas {{ $course['status'] === 'Enrolled' ? 'fa-times' : 'fa-plus' }}"></i>
                      {{ $course['status'] === 'Enrolled' ? 'Unregister' : 'Register' }}
                    </button>
                  </form>
                @elseif($course['status'] !== 'Completed')
                  <span class="not-available">Not available this semester</span>
                @else
                  <span class="completed-badge"><i class="fas fa-check"></i> Completed</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="error-state">
      <i class="fas fa-exclamation-triangle"></i>
      <p>Courses could not be loaded. Please try again later.</p>
    </div>
  @endif
</div>

<style>
  .courses-container {
    padding: 25px;
    height: calc(100vh - 120px);
    overflow-y: auto;
  }

  .courses-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
  }

  .courses-header h1 {
    color: #2c3e50;
    font-size: 24px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .semester-info {
    background-color: #f5f7fa;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
  }

  .alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
  }

  .alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
  }

  .empty-state, .error-state {
    text-align: center;
    padding: 40px 20px;
    color: #666;
  }

  .empty-state i, .error-state i {
    font-size: 40px;
    margin-bottom: 15px;
    color: #ccc;
  }

  .courses-table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow-x: auto;
  }

  .courses-table {
    width: 100%;
    border-collapse: collapse;
  }

  .courses-table th {
    background-color: #f1f1f1;
    color: #2c3e50;
    font-weight: 600;
    padding: 12px 15px;
    text-align: left;
    border-bottom: 2px solid #e0e0e0;
  }

  .courses-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: top;
  }

  .courses-table tr:last-child td {
    border-bottom: none;
  }

  .courses-table tr:hover {
    background-color: #f9f9f9;
  }

  .enrolled-course {
    background-color: #f0f7ff;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    padding: 4px 8px;
    border-radius: 12px;
  }

  .status-badge.completed {
    background-color: #d4edda;
    color: #155724;
  }

  .status-badge.enrolled {
    background-color: #d1e7ff;
    color: #0a58ca;
  }

  .status-badge.available {
    background-color: #fff3cd;
    color: #856404;
  }

  .prerequisites {
    margin-top: 8px;
  }

  .prereq-toggle {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #29a3a3;
    font-size: 13px;
    cursor: pointer;
    margin-bottom: 5px;
  }

  .prereq-toggle:hover {
    text-decoration: underline;
  }

  .prereq-list {
    display: none;
    margin: 5px 0 0 15px;
    padding: 0;
    list-style-type: none;
    font-size: 13px;
    color: #666;
  }

  .prereq-list li {
    margin-bottom: 3px;
    position: relative;
    padding-left: 15px;
  }

  .prereq-list li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #29a3a3;
  }

  .btn {
    padding: 8px 15px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    border: none;
  }

  .btn-primary {
    background-color: #29a3a3;
    color: white;
  }

  .btn-primary:hover {
    background-color: #238c8c;
  }

  .btn-danger {
    background-color: #e74c3c;
    color: white;
  }

  .btn-danger:hover {
    background-color: #c0392b;
  }

  .not-available {
    color: #666;
    font-size: 13px;
    font-style: italic;
  }

  .completed-badge {
    color: #388e3c;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  @media (max-width: 768px) {
    .courses-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
    }
    
    .courses-table th, .courses-table td {
      padding: 8px 10px;
      font-size: 14px;
    }
    
    .btn {
      padding: 6px 10px;
      font-size: 13px;
    }
  }
</style>

<script>
  function togglePrereq(element) {
    const prereqList = element.nextElementSibling;
    const icon = element.querySelector('i');
    
    if (prereqList.style.display === 'block') {
      prereqList.style.display = 'none';
      icon.classList.remove('fa-chevron-up');
      icon.classList.add('fa-chevron-down');
    } else {
      prereqList.style.display = 'block';
      icon.classList.remove('fa-chevron-down');
      icon.classList.add('fa-chevron-up');
    }
  }
</script>
@endsection