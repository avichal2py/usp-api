@extends('admin.layout')

@section('content')
<div class="enrollment-container">
  <div class="enrollment-header">
    <h2><i class="fas fa-user-graduate"></i> Enrollment Applications</h2>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @elseif(session('error'))
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
  @endif

  @if($applications->isEmpty())
    <div class="empty-state">
      <i class="fas fa-inbox"></i>
      <p>No applications available at the moment.</p>
    </div>
  @else
    <div class="application-list">
      @foreach($applications as $app)
        <div class="application-card">
          <div class="application-header">
            <h3>{{ $app->first_name }} {{ $app->last_name }}</h3>
            <span class="application-program">{{ $app->program_name }}</span>
            <span class="application-date">{{ $app->registration_date }}</span>
          </div>

          <details class="application-details">
            <summary>
              <span>View Details</span>
              <i class="fas fa-chevron-down"></i>
            </summary>
            <div class="details-content">
              <div class="details-section">
                <h4>Personal Information</h4>
                <div class="detail-row">
                  <span class="detail-label">DOB:</span>
                  <span class="detail-value">{{ $app->dob }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Gender:</span>
                  <span class="detail-value">{{ $app->gender }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Citizenship:</span>
                  <span class="detail-value">{{ $app->citizenship }}</span>
                </div>
              </div>

              <div class="details-section">
                <h4>Contact Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Email:</span>
                  <span class="detail-value">{{ $app->email_address }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">{{ $app->phone }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Residential:</span>
                  <span class="detail-value">{{ $app->residential_address }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Postal:</span>
                  <span class="detail-value">{{ $app->postal_address }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">City:</span>
                  <span class="detail-value">{{ $app->city }}, {{ $app->nation }}</span>
                </div>
              </div>

              <div class="details-section">
                <h4>Emergency Contact</h4>
                <div class="detail-row">
                  <span class="detail-label">Name:</span>
                  <span class="detail-value">{{ $app->ec_firstname }} {{ $app->ec_othername }} {{ $app->ec_lastname }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Relationship:</span>
                  <span class="detail-value">{{ $app->ec_relationship }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">{{ $app->ec_phone }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Address:</span>
                  <span class="detail-value">{{ $app->ec_residential_address }}, {{ $app->ec_city }}, {{ $app->ec_nation }}</span>
                </div>
              </div>
            </div>
          </details>

          <div class="application-actions">
            <form method="POST" action="{{ route('admin.enrollments.approve', $app->application_id) }}">
              @csrf
              <button type="submit" class="btn btn-approve">
                <i class="fas fa-check"></i> Approve
              </button>
            </form>
            <form method="POST" action="{{ route('admin.enrollments.reject', $app->application_id) }}" onsubmit="return confirm('Are you sure you want to reject this application?')">
              @csrf
              <button type="submit" class="btn btn-reject">
                <i class="fas fa-times"></i> Reject
              </button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<style>
  .enrollment-container {
    padding: 25px;
    max-width: 900px;
    margin: 0 auto;
  }

  .enrollment-header {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
  }

  .enrollment-header h2 {
    color: #2c3e50;
    font-size: 22px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
  }

  .enrollment-header h2 i {
    color: #29a3a3;
  }

  .alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
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

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #666;
  }

  .empty-state i {
    font-size: 40px;
    margin-bottom: 15px;
    color: #ccc;
  }

  .application-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  .application-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
  }

  .application-card:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }

  .application-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
  }

  .application-header h3 {
    margin: 0;
    font-size: 18px;
    color: #2c3e50;
  }

  .application-program {
    background-color: #f0f7ff;
    color: #1a73e8;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 13px;
  }

  .application-date {
    margin-left: auto;
    font-size: 13px;
    color: #666;
  }

  .application-details summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    cursor: pointer;
    color: #29a3a3;
    font-weight: 500;
    list-style: none;
  }

  .application-details summary::-webkit-details-marker {
    display: none;
  }

  .application-details summary i {
    transition: transform 0.2s ease;
  }

  .application-details[open] summary i {
    transform: rotate(180deg);
  }

  .details-content {
    padding: 15px 0;
  }

  .details-section {
    margin-bottom: 20px;
  }

  .details-section h4 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 15px;
  }

  .detail-row {
    display: flex;
    margin-bottom: 8px;
    font-size: 14px;
  }

  .detail-label {
    font-weight: 500;
    color: #555;
    min-width: 120px;
  }

  .detail-value {
    color: #333;
  }

  .application-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
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

  .btn-approve {
    background-color: #28a745;
    color: white;
  }

  .btn-approve:hover {
    background-color: #218838;
  }

  .btn-reject {
    background-color: #dc3545;
    color: white;
  }

  .btn-reject:hover {
    background-color: #c82333;
  }

  @media (max-width: 768px) {
    .application-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }

    .application-date {
      margin-left: 0;
    }

    .detail-row {
      flex-direction: column;
      gap: 2px;
    }

    .application-actions {
      flex-direction: column;
    }

    .btn {
      justify-content: center;
    }
  }
</style>
@endsection