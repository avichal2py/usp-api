@extends('lecturer.layout')

@section('content')
<div class="dashboard-container">
  <div class="dashboard-header">
    <h1><i class="fas fa-chalkboard-teacher"></i> Lecturer Dashboard</h1>
  </div>

  @if(session('user') && session('user')->role == 2)
    <div class="welcome-card">
      <div class="welcome-content">
        <div class="welcome-message">
          <h2>Welcome, <span>{{ session('user')->name }}</span></h2>
          <p class="last-login">Last login: {{ now()->format('l, F j, Y \a\t g:i A') }}</p>
        </div>
        
        <div class="user-details">
          <div class="detail-item">
            <i class="fas fa-id-card"></i>
            <div>
              <span class="detail-label">EMP ID</span>
              <span class="detail-value">{{ session('user')->emp_id }}</span>
            </div>
          </div>
          
          <div class="detail-item">
            <i class="fas fa-user-tag"></i>
            <div>
              <span class="detail-label">Role</span>
              <span class="detail-value">Lecturer</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="alert alert-warning">
      <i class="fas fa-exclamation-triangle"></i> Lecturer info not found. Please log in again.
      <a href="{{ route('login') }}" class="btn btn-primary">
        <i class="fas fa-sign-in-alt"></i> Back to Login
      </a>
    </div>
  @endif
</div>

<style>
  .dashboard-container {
    padding: 25px;
  }

  .dashboard-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
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

  .welcome-card {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
    margin-bottom: 5px;
  }

  .welcome-message h2 span {
    color: #29a3a3;
  }

  .last-login {
    color: #666;
    font-size: 14px;
  }

  .user-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 250px;
  }

  .detail-item {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .detail-item i {
    font-size: 20px;
    color: #29a3a3;
    width: 30px;
    text-align: center;
  }

  .detail-label {
    display: block;
    font-size: 13px;
    color: #666;
  }

  .detail-value {
    display: block;
    font-size: 16px;
    font-weight: 500;
    color: #2c3e50;
  }

  .alert-warning {
    background-color: #fff3cd;
    color: #856404;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    text-align: center;
  }

  .alert-warning i {
    font-size: 24px;
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
  }
</style>
@endsection