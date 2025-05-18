@extends('student.layout')

@section('content')
<div class="dashboard-container">
  <div class="dashboard-header">
    <h1><i class="fas fa-user-graduate"></i> Student Dashboard</h1>
    @php
      $unreadCount = DB::table('grade_rechecks')
        ->where('student_id', session('user')->student_id)
        ->whereIn('status', ['Reviewed', 'Rejected'])
        ->where('student_notified', false)
        ->count();
    @endphp
    <div class="notification-bell" id="notificationBell">
      <i class="fas fa-bell"></i>
      @if($unreadCount > 0)
        <span class="notification-count">{{ $unreadCount }}</span>
      @endif
    </div>
  </div>

  @if(session('user'))
    <div class="welcome-card">
      <div class="welcome-content">
        <div class="welcome-message">
          <h2>Welcome back, <span>{{ session('user')->first_name }}</span> 👋</h2>
          <p class="student-id">Student ID: {{ session('user')->student_id }}</p>
          <p class="last-login">Last login: {{ now()->format('l, F j, Y \a\t g:i A') }}</p>
        </div>
        
        <div class="quick-links">
          <a href="{{ route('student.courses') }}" class="quick-link">
            <i class="fas fa-book"></i>
            <span>My Courses</span>
          </a>
          <a href="{{ route('student.visualizeCourse') }}" class="quick-link">
            <i class="fas fa-tasks"></i>
            <span>Requirements</span>
          </a>
          <a href="{{ route('student.finance') }}" class="quick-link">
            <i class="fas fa-money-bill-wave"></i>
            <span>Finance</span>
          </a>
        </div>
      </div>
    </div>

    {{-- Grade Recheck Notifications --}}
    @php
      $notifications = DB::table('grade_rechecks')
        ->where('student_id', session('user')->student_id)
        ->whereIn('status', ['Reviewed', 'Rejected'])
        ->where('student_notified', false)
        ->get();
    @endphp

    @if ($notifications->isNotEmpty())
      <div class="notification-card" id="notificationCard">
        <div class="notification-header">
          <i class="fas fa-bell"></i>
          <h3>Grade Recheck Notifications</h3>
          <button class="close-notifications" id="closeNotifications">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="notification-list">
          @foreach ($notifications as $note)
            <div class="notification-item">
              <div class="notification-course">{{ $note->course_code }}</div>
              <div class="notification-message">
                {{ $note->lecturer_message ?? 'Your grade recheck has been processed' }}
              </div>
              <div class="notification-status {{ strtolower($note->status) }}">
                {{ $note->status }}
              </div>
            </div>
          @endforeach
        </div>

        <form method="POST" action="{{ route('student.dismissAllNotifications') }}" class="notification-actions">
          @csrf
          <input type="hidden" name="notification_ids" value="{{ $notifications->pluck('id')->implode(',') }}">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-check"></i> Mark as Read
          </button>
        </form>
      </div>
    @endif
  @endif
</div>

<style>
  .dashboard-container {
    padding: 25px;
  }

  .dashboard-header {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .dashboard-header h1 {
    color: #2c3e50;
    font-size: 24px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .dashboard-header h1 i {
    color: #29a3a3;
  }

  .notification-bell {
    position: relative;
    font-size: 20px;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px;
  }

  .notification-bell:hover {
    color: #29a3a3;
    background-color: #f5f7fa;
    border-radius: 50%;
  }

  .notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
  }

  .welcome-card {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 30px;
  }

  .welcome-content {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
  }

  .welcome-message {
    flex: 1;
    min-width: 300px;
  }

  .welcome-message h2 {
    font-size: 22px;
    color: #2c3e50;
    margin-bottom: 10px;
  }

  .welcome-message h2 span {
    color: #29a3a3;
  }

  .student-id {
    color: #666;
    font-size: 15px;
    margin-bottom: 5px;
  }

  .last-login {
    color: #666;
    font-size: 14px;
  }

  .quick-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    min-width: 300px;
  }

  .quick-link {
    background-color: #f5f7fa;
    border-radius: 6px;
    padding: 15px 10px;
    text-align: center;
    color: #2c3e50;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .quick-link:hover {
    background-color: #29a3a3;
    color: white;
  }

  .quick-link i {
    font-size: 24px;
    margin-bottom: 8px;
  }

  .quick-link span {
    font-size: 14px;
  }

  .notification-card {
    background: white;
    border-radius: 8px;
    padding: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    margin-bottom: 20px;
  }

  .notification-card.show {
    max-height: 1000px;
    padding: 25px;
  }

  .notification-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .notification-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .notification-header i {
    color: #f59e0b;
    font-size: 20px;
  }

  .close-notifications {
    background: none;
    border: none;
    color: #666;
    font-size: 16px;
    cursor: pointer;
    padding: 5px;
    transition: all 0.2s ease;
  }

  .close-notifications:hover {
    color: #e74c3c;
  }

  .notification-list {
    margin-bottom: 20px;
  }

  .notification-item {
    padding: 15px;
    border-radius: 6px;
    background-color: #fef3c7;
    margin-bottom: 10px;
    border-left: 4px solid #f59e0b;
  }

  .notification-course {
    font-weight: 600;
    color: #b45309;
    margin-bottom: 5px;
  }

  .notification-message {
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
  }

  .notification-status {
    font-size: 13px;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 12px;
    display: inline-block;
  }

  .notification-status.reviewed {
    background-color: #d1fae5;
    color: #065f46;
  }

  .notification-status.rejected {
    background-color: #fee2e2;
    color: #b91c1c;
  }

  .notification-actions {
    display: flex;
    justify-content: flex-end;
  }

  .btn {
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
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

  @media (max-width: 768px) {
    .welcome-content {
      flex-direction: column;
      gap: 20px;
    }
    
    .quick-links {
      grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    }
  }


  .notification-card {
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    padding: 0 25px;
  }

  .notification-card.show {
    max-height: 1000px;
    opacity: 1;
    padding: 25px;
    margin-top: 20px;
    display: block;
  }

  .notification-bell {
    position: relative;
    cursor: pointer;
  }

  .notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notificationBell');
    const notificationCard = document.getElementById('notificationCard');
    const closeButton = document.getElementById('closeNotifications');
    
    if (notificationBell && notificationCard) {
      // Toggle notifications when bell is clicked
      notificationBell.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationCard.classList.toggle('show');
        
        // Change bell icon style when notifications are visible
        const bellIcon = this.querySelector('i');
        if (notificationCard.classList.contains('show')) {
          bellIcon.classList.remove('fa-bell');
          bellIcon.classList.add('fa-bell-slash');
        } else {
          bellIcon.classList.remove('fa-bell-slash');
          bellIcon.classList.add('fa-bell');
        }
      });

      // Close notifications when X button is clicked
      if (closeButton) {
        closeButton.addEventListener('click', function(e) {
          e.stopPropagation();
          notificationCard.classList.remove('show');
          const bellIcon = notificationBell.querySelector('i');
          bellIcon.classList.remove('fa-bell-slash');
          bellIcon.classList.add('fa-bell');
        });
      }

      // Close notifications when clicking outside
      document.addEventListener('click', function(e) {
        if (!notificationCard.contains(e.target) && 
            !notificationBell.contains(e.target) && 
            notificationCard.classList.contains('show')) {
          notificationCard.classList.remove('show');
          const bellIcon = notificationBell.querySelector('i');
          bellIcon.classList.remove('fa-bell-slash');
          bellIcon.classList.add('fa-bell');
        }
      });
    }
  });
</script>
@endsection