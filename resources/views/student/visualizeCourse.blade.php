@extends('student.layout')

@section('content')

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif



<div class="visualization-container">
  <div class="visualization-header">
    <h1><i class="fas fa-project-diagram"></i> Course Progress Visualization</h1>
    <div class="student-info">
      <div class="info-item">
        <span class="info-label">Student:</span>
        <span class="info-value">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</span>
      </div>
      <div class="info-item">
        <span class="info-label">Program:</span>
        <span class="info-value">{{ $student->program_name ?? 'N/A' }}</span>
      </div>
    </div>
  </div>

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
    <div class="progress-header">
      <h3>Overall Completion Progress</h3>
      <a href="{{ route('student.downloadCompletedCourses') }}" class="download-btn">
        <i class="fas fa-file-download"></i> Download Report
      </a>
    </div>
    
    <div class="progress-stats">
      <div class="stat-item">
        <span class="stat-number">{{ $completedCount }}</span>
        <span class="stat-label">Completed</span>
      </div>
      <div class="stat-item">
        <span class="stat-number">{{ $totalCount - $completedCount }}</span>
        <span class="stat-label">Remaining</span>
      </div>
      <div class="stat-item">
        <span class="stat-number">{{ $totalCount }}</span>
        <span class="stat-label">Total</span>
      </div>
      <div class="stat-item highlight">
        <span class="stat-number">{{ $percent }}%</span>
        <span class="stat-label">Complete</span>
      </div>
    </div>

    <div class="progress-bar-container">
      <div class="progress-bar" style="width: {{ $percent }}%;"></div>
    </div>
  </div>

  <div class="legend">
    <div class="legend-item">
      <div class="legend-color completed"></div>
      <span>Completed</span>
    </div>
    <div class="legend-item">
      <div class="legend-color enrolled"></div>
      <span>Enrolled</span>
    </div>
    <div class="legend-item">
      <div class="legend-color not-started"></div>
      <span>Not Started</span>
    </div>
    <div class="legend-item">
      <div class="legend-color prerequisite"></div>
      <span>Prerequisite Needed</span>
    </div>
  </div>

  <div class="levels-container">
    @foreach ($levels as $level)
      <div class="level-card">
        <div class="level-header">
          <h2>Level {{ $level['level'] }}</h2>
          @php
            $levelCompleted = 0;
            $levelTotal = count($level['courses']);
            foreach ($level['courses'] as $course) {
              if ($course['status'] === 'Completed') $levelCompleted++;
            }
            $levelPercent = $levelTotal > 0 ? round(($levelCompleted / $levelTotal) * 100) : 0;
          @endphp
          <div class="level-progress">
            <span>{{ $levelCompleted }}/{{ $levelTotal }} ({{ $levelPercent }}%)</span>
            <div class="mini-progress-bar">
              <div class="mini-progress-fill" style="width: {{ $levelPercent }}%;"></div>
            </div>
          </div>
        </div>

        <div class="courses-table">
          <table>
            <thead>
              <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Status</th>
                <th>Grade</th>
                <th>Semester</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($level['courses'] as $course)
                <tr class="
                  {{ $course['status'] === 'Completed' ? 'completed' : '' }}
                  {{ $course['status'] === 'Enrolled' ? 'enrolled' : '' }}
                  {{ $course['status'] === 'Not Started' ? 'not-started' : '' }}
                  {{ !empty($course['missing_prerequisites']) ? 'has-prerequisites' : '' }}
                ">
                  <td>{{ $course['course_code'] }}</td>
                  <td>
                    {{ $course['label'] }}
                    @if(!empty($course['missing_prerequisites']))
                      <div class="prerequisites-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <div class="tooltip-text">
                          <strong>Missing Prerequisites:</strong>
                          <ul>
                            @foreach($course['missing_prerequisites'] as $prereq)
                              <li>{{ $prereq }}</li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                    @endif
                  </td>
                  <td>
                    <span class="status-badge {{ strtolower(str_replace(' ', '-', $course['status'])) }}">
                      @if($course['status'] === 'Completed')
                        <i class="fas fa-check-circle"></i>
                      @elseif($course['status'] === 'Enrolled')
                        <i class="fas fa-user-graduate"></i>
                      @else
                        <i class="fas fa-clock"></i>
                      @endif
                      {{ $course['status'] }}
                    </span>
                  </td>
                  <td>{{ $course['grade'] ?? '—' }}</td>
                  <td>{{ $course['semester'] ?? '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach
  </div>
</div>

<style>
  .alert {
      padding: 15px;
      margin: 20px 0;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 500;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  .visualization-container {
    padding: 25px;
    height: calc(100vh - 120px);
    overflow-y: auto;
  }

  .visualization-header {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
  }

  .visualization-header h1 {
    color: #2c3e50;
    font-size: 24px;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .student-info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }

  .info-item {
    display: flex;
    gap: 5px;
    font-size: 15px;
  }

  .info-label {
    font-weight: 500;
    color: #555;
  }

  .info-value {
    color: #2c3e50;
  }

  .progress-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
  }

  .progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .progress-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
  }

  .download-btn {
    background-color: #29a3a3;
    color: white;
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }

  .download-btn:hover {
    background-color: #238c8c;
  }

  .progress-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    flex-wrap: wrap;
  }

  .stat-item {
    background-color: #f5f7fa;
    padding: 10px 15px;
    border-radius: 6px;
    min-width: 100px;
    text-align: center;
  }

  .stat-item.highlight {
    background-color: #e8f5e9;
    border-left: 4px solid #4caf50;
  }

  .stat-number {
    font-size: 20px;
    font-weight: 600;
    color: #2c3e50;
    display: block;
  }

  .stat-label {
    font-size: 13px;
    color: #666;
  }

  .progress-bar-container {
    height: 10px;
    background-color: #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
  }

  .progress-bar {
    height: 100%;
    background-color: #4caf50;
    transition: width 0.5s ease;
  }

  .legend {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    flex-wrap: wrap;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
  }

  .legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
  }

  .legend-color.completed {
    background-color: #d4edda;
  }

  .legend-color.enrolled {
    background-color: #cce5ff;
  }

  .legend-color.not-started {
    background-color: #f2f2f2;
  }

  .legend-color.prerequisite {
    background-color: #fff3cd;
  }

  .levels-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .level-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
  }

  .level-header {
    background-color: #f5f7fa;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e0e0e0;
  }

  .level-header h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
  }

  .level-progress {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #555;
  }

  .mini-progress-bar {
    width: 100px;
    height: 6px;
    background-color: #e0e0e0;
    border-radius: 3px;
    overflow: hidden;
  }

  .mini-progress-fill {
    height: 100%;
    background-color: #4caf50;
  }

  .courses-table {
    width: 100%;
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th {
    background-color: #f1f1f1;
    color: #2c3e50;
    font-weight: 600;
    padding: 12px 15px;
    text-align: left;
    border-bottom: 2px solid #e0e0e0;
  }

  td {
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
  }

  tr:last-child td {
    border-bottom: none;
  }

  tr.completed {
    background-color: #f8fdf8;
  }

  tr.enrolled {
    background-color: #f5faff;
  }

  tr.not-started {
    background-color: #fafafa;
  }

  tr.has-prerequisites {
    background-color: #fffcf5;
  }

  tr:hover {
    background-color: #f9f9f9;
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

  .status-badge.not-started {
    background-color: #f2f2f2;
    color: #555;
  }

  .prerequisites-tooltip {
    display: inline-block;
    position: relative;
    margin-left: 8px;
    color: #f59e0b;
    cursor: pointer;
  }

  .prerequisites-tooltip .tooltip-text {
    visibility: hidden;
    width: 250px;
    background-color: #fff;
    color: #333;
    text-align: left;
    border-radius: 6px;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 13px;
    border: 1px solid #eee;
  }

  .prerequisites-tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
  }

  .prerequisites-tooltip .tooltip-text ul {
    margin: 5px 0 0 15px;
    padding: 0;
  }

  @media (max-width: 768px) {
    .visualization-container {
      padding: 15px;
    }

    .student-info {
      flex-direction: column;
      gap: 5px;
    }

    .progress-stats {
      gap: 10px;
    }

    .stat-item {
      min-width: 80px;
      padding: 8px 10px;
    }

    .legend {
      gap: 10px;
    }

    th, td {
      padding: 8px 10px;
      font-size: 14px;
    }
  }
</style>
@endsection