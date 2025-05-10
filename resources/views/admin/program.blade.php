@extends('admin.layout')

@section('content')
<div class="program-container">
  <h2 class="program-title">
    <i class="fas fa-graduation-cap"></i> Program Registration Management
  </h2>

  @if(session('success'))
    <div class="program-alert success">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  <div class="program-list">
    @foreach ($programs as $program)
      <div class="program-item">
        <div class="program-details">
          <span class="program-name">{{ $program->program_name }}</span>
          <span class="program-status {{ $program->registration_status ? 'status-open' : 'status-closed' }}">
            {{ $program->registration_status ? 'Open' : 'Closed' }}
          </span>
        </div>
        <form method="POST" action="{{ route('admin.programs.toggle', $program->program_id) }}">
          @csrf
          <button type="submit" class="toggle-btn {{ $program->registration_status ? 'btn-close' : 'btn-open' }}">
            {{ $program->registration_status ? 'Close Registration' : 'Open Registration' }}
          </button>
        </form>
      </div>
    @endforeach
  </div>
</div>

<style>
  .program-container {
    padding: 25px;
    max-width: 800px;
    margin: 0 auto;
  }

  .program-title {
    color: #2c3e50;
    font-size: 22px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .program-title i {
    color: #29a3a3;
  }

  .program-alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
  }

  .program-alert.success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
  }

  .program-alert i {
    font-size: 16px;
  }

  .program-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .program-item {
    background: white;
    border-radius: 6px;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
  }

  .program-item:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }

  .program-details {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .program-name {
    font-size: 16px;
    font-weight: 500;
    color: #2c3e50;
  }

  .program-status {
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 500;
  }

  .status-open {
    background-color: #d4edda;
    color: #155724;
  }

  .status-closed {
    background-color: #f8d7da;
    color: #721c24;
  }

  .toggle-btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
  }

  .btn-open {
    background-color: #28a745;
    color: white;
  }

  .btn-open:hover {
    background-color: #218838;
  }

  .btn-close {
    background-color: #dc3545;
    color: white;
  }

  .btn-close:hover {
    background-color: #c82333;
  }

  @media (max-width: 600px) {
    .program-item {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
    
    .toggle-btn {
      width: 100%;
    }
  }
</style>
@endsection