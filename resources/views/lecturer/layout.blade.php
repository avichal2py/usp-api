<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lecturer Panel</title>
  <style>
    :root {
      --teal-primary: #29a3a3;
      --teal-dark: #238c8c;
      --teal-darker: #1e7d7d;
      --red-primary: #e74c3c;
      --red-dark: #c0392b;
      --text-light: #ffffff;
      --text-dark: #333333;
      --bg-light: #f5f7fa;
      --sidebar-width: 250px;
      --header-height: 70px;
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
    }

    /* Layout Structure */
    .app-container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
      width: var(--sidebar-width);
      background-color: var(--teal-primary);
      color: var(--text-light);
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      z-index: 100;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
      padding: 12px 25px;
      text-decoration: none;
      color: var(--text-light);
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 5px 10px;
      border-radius: 5px;
      transition: all 0.2s ease;
    }

    .nav-link:hover {
      background-color: var(--teal-dark);
    }

    .nav-link.active {
      background-color: var(--teal-darker);
      font-weight: 500;
      box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
    }

    .notification-badge {
      background-color: var(--red-primary);
      color: var(--text-light);
      font-weight: bold;
      font-size: 0.75rem;
      padding: 2px 8px;
      border-radius: 12px;
      margin-left: auto;
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
      transition: all 0.2s ease;
    }

    .logout-btn:hover {
      background-color: var(--red-dark);
    }

    /* Main Content Area */
    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Header Styles - Fixed alignment */
    .top-header {
      background-color: #ffffff;
      padding: 0 30px;
      height: var(--header-height);
      display: flex;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 90;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
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
      flex-shrink: 0; /* Prevents avatar from shrinking */
    }

    .user-name {
      font-weight: 500;
      color: var(--text-dark);
      white-space: nowrap; /* Prevents text from wrapping */
    }

    /* Content Area */
    .content-wrapper {
      flex: 1;
      padding: 30px;
    }

    .content-area {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      min-height: calc(100vh - var(--header-height) - 60px);
      padding: 25px;
    }

    .page-header {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 25px;
      color: var(--text-dark);
    }

    /* Footer Styles */
    .main-footer {
      background-color: #ffffff;
      padding: 15px 30px;
      text-align: center;
      font-size: 0.85rem;
      color: #666;
      border-top: 1px solid #eee;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
        overflow: hidden;
      }
      
      .sidebar-header h2, .nav-link span, .logout-btn span {
        display: none;
      }
      
      .nav-link {
        justify-content: center;
        padding: 12px 5px;
        margin: 5px;
      }
      
      .notification-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        margin: 0;
      }
      
      .main-content {
        margin-left: 70px;
      }

      .content-wrapper {
        padding: 15px;
      }

      .content-area {
        padding: 20px;
      }

      .user-name {
        display: none;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <div class="app-container">
    <div class="sidebar">
      <div class="sidebar-header">
        <h2>Lecturer Panel</h2>
      </div>
      
      <nav class="nav-menu">
        <a href="{{ route('lecturer.home') }}" class="nav-link {{ request()->routeIs('lecturer.home') ? 'active' : '' }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('lecturer.grade') }}" class="nav-link {{ request()->routeIs('lecturer.grade') ? 'active' : '' }}">
          <i class="fas fa-graduation-cap"></i>
          <span>Grade Students</span>
        </a>
        <a href="{{ route('lecturer.gradeRechecks') }}" class="nav-link {{ request()->routeIs('lecturer.gradeRechecks') ? 'active' : '' }}">
          <i class="fas fa-redo"></i>
          <span>Grade Rechecks</span>
          @if(isset($recheckCount) && $recheckCount > 0)
            <span class="notification-badge">{{ $recheckCount }}</span>
          @endif
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
      <header class="top-header">
        <div class="user-info">
          <span class="user-name">Welcome, {{ Auth::user()->name ?? 'Lecturer' }}</span>
          <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'L', 0, 1)) }}</div>
        </div>
      </header>

      <div class="content-wrapper">
        <main class="content-area">
          @yield('content')
        </main>
      </div>

      <footer class="main-footer">
        <p>&copy; {{ date('Y') }} University Lecturer Panel. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>