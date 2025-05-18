<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
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
      --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      --transition: all 0.2s ease;
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
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header {
      background-color: white;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow);
      z-index: 10;
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
      gap: 10px;
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: var(--teal-primary);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    .container {
      display: flex;
      flex: 1;
    }

    .sidebar {
      width: 220px;
      background-color: var(--teal-primary);
      color: var(--text-light);
      display: flex;
      flex-direction: column;
      padding-top: 20px;
      flex-shrink: 0;
    }

    .sidebar-header {
      padding: 0 20px 20px;
      margin-bottom: 10px;
      text-align: center;
    }

    .sidebar-title {
      font-size: 1.2rem;
      font-weight: 500;
    }

    .nav-menu {
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .nav-link {
      padding: 12px 20px;
      text-decoration: none;
      color: var(--text-light);
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 2px 10px;
      border-radius: 4px;
      transition: var(--transition);
    }

    .nav-link:hover {
      background-color: var(--teal-dark);
    }

    .nav-link.active {
      background-color: var(--teal-darker);
      font-weight: 500;
      box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .nav-link i {
      width: 20px;
      text-align: center;
    }

    .logout-container {
      margin-top: auto;
      padding: 10px;
    }

    .logout-btn {
      width: 100%;
      padding: 12px;
      background-color: var(--red-primary);
      text-align: center;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: var(--transition);
    }

    .logout-btn:hover {
      background-color: var(--red-dark);
    }

    .main-content {
      flex: 1;
      padding: 30px;
      background-color: white;
      margin: 20px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .footer {
      background-color: white;
      padding: 15px 30px;
      text-align: center;
      font-size: 0.85rem;
      color: #666;
      border-top: 1px solid #eaeaea;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 60px;
        padding-top: 15px;
      }
      
      .sidebar-header, .nav-link span, .logout-btn span {
        display: none;
      }
      
      .nav-link {
        justify-content: center;
        padding: 12px 5px;
        margin: 2px 5px;
      }
      
      .logo-text {
        display: none;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <header class="header">
    <div class="logo-container">
      <img src="{{ asset('images/icon.jpg') }}" alt="USP Logo" class="logo">
      <span class="logo-text">Admin Panel</span>
    </div>
    <div class="user-info">
      <span>Administrator</span>
      <div class="user-avatar">A</div>
    </div>
  </header>

  <div class="container">
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2 class="sidebar-title">Menu</h2>
      </div>
      
      <nav class="nav-menu">
        <a href="{{ route('admin.home') }}" class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.semester') }}" class="nav-link {{ request()->routeIs('admin.semester') ? 'active' : '' }}">
          <i class="fas fa-calendar-alt"></i>
          <span>Change Semester</span>
        </a>
        <a href="{{ route('admin.program') }}" class="nav-link {{ request()->routeIs('admin.program') ? 'active' : '' }}">
          <i class="fas fa-graduation-cap"></i>
          <span>Program Management</span>
        </a>
        <a href="{{ route('admin.enrollments') }}" class="nav-link {{ request()->routeIs('admin.enrollments') ? 'active' : '' }}">
          <i class="fas fa-users"></i>
          <span>Enrollments</span>
        </a>
        <a href="{{ route('admin.restrict.view') }}" class="nav-link {{ request()->routeIs('admin.restrict.view') ? 'active' : '' }}">
          <i class="fas fa-dollar"></i>
          <span>Pending Fee</span>
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
    </aside>

    <main class="main-content">
      @yield('content')
    </main>
  </div>

  <footer class="footer">
    <p>&copy; {{ date('Y') }} University Admin Panel. All rights reserved.</p>
  </footer>

</body>
</html>