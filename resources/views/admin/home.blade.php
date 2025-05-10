@extends('admin.layout')

@section('content')
<div class="dashboard-container">
  <div class="dashboard-header">
    <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
    @if(session('user'))
      <div class="welcome-message">
        Welcome back, <strong>{{ session('user')->name }}</strong>
        <div class="last-login">Last login: {{ now()->format('M j, Y \a\t g:i A') }}</div>
      </div>
    @endif
  </div>

  @if(!session('user'))
    <div class="alert alert-warning">
      <i class="fas fa-exclamation-triangle"></i> Admin info not found. Please log in again.
      <a href="{{ route('login') }}" class="btn btn-primary">Back to Login</a>
    </div>
  @else
    <div class="dashboard-stats">
      <div class="stat-card">
        <div class="stat-icon bg-teal">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
          <h3>1,248</h3>
          <p>Total Students</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon bg-blue">
          <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-info">
          <h3>42</h3>
          <p>Active Programs</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon bg-orange">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
          <h3>Semester 1</h3>
          <p>Current Semester</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon bg-red">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-info">
          <h3>12</h3>
          <p>Pending Actions</p>
        </div>
      </div>
    </div>

    <div class="dashboard-sections">
      <div class="recent-activity">
        <h2><i class="fas fa-history"></i> Recent Activity</h2>
        <ul class="activity-list">
          <li>
            <i class="fas fa-user-plus activity-icon"></i>
            <div class="activity-details">
              <span>New student registration</span>
              <small>10 minutes ago</small>
            </div>
          </li>
          <li>
            <i class="fas fa-edit activity-icon"></i>
            <div class="activity-details">
              <span>Course curriculum updated</span>
              <small>2 hours ago</small>
            </div>
          </li>
          <li>
            <i class="fas fa-envelope activity-icon"></i>
            <div class="activity-details">
              <span>System notification sent</span>
              <small>Yesterday</small>
            </div>
          </li>
        </ul>
      </div>

      <div class="quick-actions">
        <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
        <div class="action-buttons">
          <a href="{{ route('admin.semester') }}" class="action-btn">
            <i class="fas fa-calendar-alt"></i>
            <span>Change Semester</span>
          </a>
          <a href="{{ route('admin.enrollments') }}" class="action-btn">
            <i class="fas fa-user-plus"></i>
            <span>New Enrollment</span>
          </a>
          <a href="#" class="action-btn">
            <i class="fas fa-file-export"></i>
            <span>Generate Report</span>
          </a>
          <a href="#" class="action-btn">
            <i class="fas fa-bell"></i>
            <span>Send Notification</span>
          </a>
        </div>
      </div>
    </div>
  @endif
</div>

<style>
  .dashboard-container {
    padding: 25px;
  }

  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
  }

  .dashboard-header h1 {
    color: #2c3e50;
    font-size: 24px;
    margin: 0;
  }

  .dashboard-header h1 i {
    margin-right: 10px;
    color: #29a3a3;
  }

  .welcome-message {
    text-align: right;
  }

  .last-login {
    font-size: 13px;
    color: #666;
  }

  .alert-warning {
    background-color: #fff3cd;
    color: #856404;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .alert-warning i {
    margin-right: 10px;
  }

  .btn {
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
  }

  .btn-primary {
    background-color: #29a3a3;
    color: white;
  }

  .btn-primary:hover {
    background-color: #238c8c;
  }

  .dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  .stat-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
  }

  .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 20px;
  }

  .bg-teal { background-color: #29a3a3; }
  .bg-blue { background-color: #3498db; }
  .bg-orange { background-color: #e67e22; }
  .bg-red { background-color: #e74c3c; }

  .stat-info h3 {
    margin: 0;
    font-size: 22px;
    color: #2c3e50;
  }

  .stat-info p {
    margin: 5px 0 0;
    color: #666;
    font-size: 14px;
  }

  .dashboard-sections {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
  }

  @media (max-width: 768px) {
    .dashboard-sections {
      grid-template-columns: 1fr;
    }
  }

  .recent-activity, .quick-actions {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  }

  .recent-activity h2, .quick-actions h2 {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 20px;
    color: #2c3e50;
  }

  .recent-activity h2 i, .quick-actions h2 i {
    margin-right: 10px;
    color: #29a3a3;
  }

  .activity-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .activity-list li {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .activity-list li:last-child {
    border-bottom: none;
  }

  .activity-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #f5f7fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: #29a3a3;
    font-size: 14px;
  }

  .activity-details {
    flex: 1;
  }

  .activity-details span {
    display: block;
    font-size: 14px;
  }

  .activity-details small {
    color: #999;
    font-size: 12px;
  }

  .action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
  }

  .action-btn {
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

  .action-btn:hover {
    background-color: #29a3a3;
    color: white;
  }

  .action-btn i {
    font-size: 20px;
    margin-bottom: 8px;
  }

  .action-btn span {
    font-size: 13px;
  }
</style>
@endsection