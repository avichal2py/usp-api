@extends('admin.layout')

@section('content')
<div class="semester-container">
  <div class="semester-card">
    <div class="semester-header">
      <i class="fas fa-calendar-alt"></i>
      <h2>Change Current Semester</h2>
    </div>

    @if (session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.semester.update') }}" class="semester-form">
      @csrf

      <div class="form-group">
        <label for="semester">
          <i class="fas fa-chevron-circle-down"></i> Select Semester
        </label>
        <select name="semester" id="semester" required>
          <option value="1" {{ $currentSemester == '1' ? 'selected' : '' }}>Semester 1</option>
          <option value="2" {{ $currentSemester == '2' ? 'selected' : '' }}>Semester 2</option>
        </select>
      </div>

      <button type="submit" class="submit-btn">
        <i class="fas fa-sync-alt"></i> Update Semester
      </button>
    </form>

    <div class="current-semester">
      <i class="fas fa-info-circle"></i>
      Current Semester: <strong>Semester {{ $currentSemester }}</strong>
    </div>
  </div>
</div>

<style>
  .semester-container {
    padding: 30px;
    display: flex;
    justify-content: center;
    min-height: calc(100vh - 60px);
  }

  .semester-card {
    background: white;
    border-radius: 10px;
    padding: 30px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  }

  .semester-header {
    text-align: center;
    margin-bottom: 25px;
    color: #2c3e50;
  }

  .semester-header i {
    font-size: 32px;
    color: #29a3a3;
    margin-bottom: 10px;
  }

  .semester-header h2 {
    margin: 0;
    font-size: 22px;
  }

  .alert {
    padding: 12px 15px;
    border-radius: 6px;
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

  .semester-form {
    margin: 25px 0;
  }

  .form-group {
    margin-bottom: 20px;
  }

  label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  label i {
    color: #29a3a3;
  }

  select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #f9f9f9;
    font-size: 15px;
    transition: all 0.2s ease;
  }

  select:focus {
    border-color: #29a3a3;
    outline: none;
    box-shadow: 0 0 0 3px rgba(41, 163, 163, 0.2);
  }

  .submit-btn {
    width: 100%;
    padding: 12px;
    background-color: #29a3a3;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
  }

  .submit-btn:hover {
    background-color: #238c8c;
    transform: translateY(-1px);
  }

  .submit-btn:active {
    transform: translateY(0);
  }

  .current-semester {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .current-semester i {
    color: #29a3a3;
  }

  .current-semester strong {
    color: #2c3e50;
  }

  @media (max-width: 576px) {
    .semester-container {
      padding: 20px;
    }
    
    .semester-card {
      padding: 20px;
    }
  }
</style>
@endsection