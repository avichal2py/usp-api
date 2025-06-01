<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Panel</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/icon.jpg') }}">

  <style>
    :root {
      --teal-primary: #29a3a3;
      --teal-dark: #238c8c;
      --teal-darker: #1e7d7d;
      --red-primary: #e74c3c;
      --red-dark: #c0392b;
      --text-light: #ffffff;
      --text-dark: #333333;
      --text-muted: #666666;
      --bg-light: #f5f7fa;
      --border-color: #eaeaea;
      --sidebar-width: 220px;
      --header-height: 70px;
      --shadow: 0 2px 10px rgba(0,0,0,0.08);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-dark);
      background-color: var(--bg-light);
      min-height: 100vh;
      line-height: 1.5;
    }

    /* Layout Structure */
    .app-container {
      display: flex;
      min-height: calc(100vh - var(--header-height));
      margin-top: var(--header-height);
    }

    /* Sidebar Styles */
    .sidebar {
      width: var(--sidebar-width);
      background-color: var(--teal-primary);
      color: var(--text-light);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: fixed;
      height: calc(100vh - var(--header-height));
      overflow-y: auto;
    }

    .sidebar-header {
      padding: 20px;
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-header h2 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
    }

    .nav-menu {
      flex: 1;
      padding: 15px 0;
    }

    .nav-link {
      padding: 12px 20px;
      text-decoration: none;
      color: var(--text-light);
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 5px 10px;
      border-radius: 5px;
      transition: var(--transition);
      font-size: 0.95rem;
    }

    .nav-link:hover {
      background-color: var(--teal-dark);
    }

    .nav-link.active {
      background-color: var(--teal-darker);
      font-weight: 500;
    }

    .logout-container {
      padding: 15px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    .logout-btn {
      width: 100%;
      padding: 10px;
      background-color: var(--red-primary);
      color: var(--text-light);
      border: none;
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: var(--transition);
      font-size: 0.95rem;
    }

    .logout-btn:hover {
      background-color: var(--red-dark);
    }

    /* Main Content Area */
    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      padding: 30px;
      min-height: calc(100vh - var(--header-height));
    }

    /* Header Styles */
    .top-header {
      background-color: white;
      padding: 0 30px;
      height: var(--header-height);
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo {
      height: 40px;
      width: auto;
    }

    .logo-text {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--teal-primary);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--teal-primary);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 16px;
    }

    .user-name {
      font-weight: 500;
      color: var(--text-dark);
    }

    /* Content Area */
    .content-wrapper {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: var(--shadow);
      padding: 25px;
      margin-bottom: 30px;
    }

    .page-header {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 25px;
      color: var(--text-dark);
    }

    /* Footer Styles */
    .main-footer {
      background-color: white;
      padding: 15px 30px;
      text-align: center;
      font-size: 0.85rem;
      color: var(--text-muted);
      border-top: 1px solid var(--border-color);
      margin-left: var(--sidebar-width);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
      }
      
      .sidebar-header h2, 
      .nav-link span, 
      .logout-btn span,
      .user-name {
        display: none;
      }
      
      .nav-link {
        justify-content: center;
        padding: 12px 5px;
        margin: 5px;
      }
      
      .main-content {
        margin-left: 70px;
        padding: 20px;
      }

      .content-wrapper {
        padding: 20px;
      }

      .main-footer {
        margin-left: 70px;
        padding: 15px;
      }
    }

    @media (max-width: 480px) {
      .logo-text {
        display: none;
      }
      
      .top-header {
        padding: 0 15px;
      }
      
      .main-content {
        padding: 15px;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <header class="top-header">
    <div class="logo-container">
      <img src="{{ asset('images/icon.jpg') }}" alt="USP Logo" class="logo">
      <span class="logo-text">Student Panel</span>
    </div>
    <div class="user-info">
      <span class="user-name">{{ Auth::user()->name ?? 'Student' }}</span>
      <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}</div>
    </div>
  </header>

  <div class="app-container">
    <div class="sidebar">
      <div class="sidebar-header">
        <h2>Menu</h2>
      </div>
      
      <nav class="nav-menu">
        <a href="{{ route('student.home') }}" class="nav-link {{ request()->routeIs('student.home') ? 'active' : '' }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('student.courses') }}" class="nav-link {{ request()->routeIs('student.courses') ? 'active' : '' }}">
          <i class="fas fa-book"></i>
          <span>Courses</span>
        </a>
        <a href="{{ route('student.visualizeCourse') }}" class="nav-link {{ request()->routeIs('student.visualizeCourse') ? 'active' : '' }}">
          <i class="fas fa-tasks"></i>
          <span>My Requirements</span>
        </a>
        <a href="{{ route('student.finance') }}" class="nav-link {{ request()->routeIs('student.finance') ? 'active' : '' }}">
          <i class="fas fa-money-bill-wave"></i>
          <span>Finance</span>
        </a>
        <a href="{{ route('student.recheckCourses') }}" class="nav-link {{ request()->routeIs('student.recheckCourses') ? 'active' : '' }}">
          <i class="fas fa-redo"></i>
          <span>Grade Recheck</span>
        </a>
        <a href="{{ route('student.requestForm') }}" class="nav-link {{ request()->routeIs('student.requestForm') ? 'active' : '' }}">
          <i class="fas fa-file"></i>
          <span>Applications</span>
        </a>
      </nav>

      <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="logout-btn" type="submit">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </div>

    <div class="main-content">
      <div class="content-wrapper">
        <main class="content-area">
          @yield('content')
        </main>
      </div>
    </div>
  </div>

  <footer class="main-footer">
    <p>&copy; {{ date('Y') }} University Student Panel. All rights reserved.</p>
  </footer>
</body>
</html>