<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Panel</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 220px;
      background-color: #29a3a3;
      color: white;
      display: flex;
      flex-direction: column;
      padding-top: 20px;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 20px;
    }

    .nav-link {
      padding: 12px 20px;
      text-decoration: none;
      color: white;
      display: block;
    }

    .nav-link:hover {
      background-color: #238c8c;
    }

    .nav-link.active {
      background-color: #1e7d7d;
      box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2); 
      font-weight: bold; 
    }

    .logout-btn {
      margin-top: auto;
      padding: 12px 20px;
      background-color: #e74c3c;
      text-align: center;
      color: white;
      border: none;
      cursor: pointer;
      width: 100%;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }

    .main-content {
      flex: 1;
      padding: 1%;
      background-color: #f5f7fa;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Student Panel</h2>
    <a href="{{ route('student.home') }}" class="nav-link {{ request()->routeIs('student.home') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('student.courses') }}" class="nav-link {{ request()->routeIs('student.courses') ? 'active' : '' }}">Courses</a>
    <a href="{{ route('student.visualizeCourse') }}" class="nav-link {{ request()->routeIs('student.visualizeCourse') ? 'active' : '' }}">My Requirements</a>
    <a href="{{ route('student.finance') }}" class="nav-link {{ request()->routeIs('student.finance') ? 'active' : '' }}">Finance</a>
    <a href="{{ route('student.recheckCourses') }}" class="nav-link {{ request()->routeIs('student.recheckCourses') ? 'active' : '' }}">Grade Recheck</a>


    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="logout-btn" type="submit">Logout</button>
    </form>
  </div>

  <div class="main-content">
    @yield('content')
  </div>

</body>
</html>
