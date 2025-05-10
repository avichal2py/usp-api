
@extends('lecturer.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lecturer Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 700px;
      margin: 80px auto;
      padding: 24px;
      text-align: center;
    }

    .title {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .info {
      font-size: 16px;
      margin-bottom: 12px;
      color: #333;
    }

    .alert {
      background-color: #ffe6e6;
      color: #c0392b;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .btn {
      padding: 10px 20px;
      background-color: #d9534f;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      margin-top: 20px;
    }

    .btn-secondary {
      background-color: #3498db;
    }
  </style>
</head>
<body>

  <div class="container">
    @if(session('user') && session('user')->role == 2)
      <div class="title">Lecturer Dashboard</div>
      <div class="info">Welcome, {{ session('user')->name }}</div>
      <div class="info">EMP ID: {{ session('user')->emp_id }}</div>
      <div class="info">Role: Lecturer</div>

    @else
      <div class="alert">Lecturer info not found. Please log in again.</div>
      <a href="{{ route('login') }}">
        <button class="btn btn-secondary">Back to Login</button>
      </a>
    @endif
  </div>

</body>
</html>
@endsection
