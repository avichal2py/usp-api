<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>USP Portal Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, rgba(41,163,163,0.9) 0%, rgba(20,95,140,0.9) 100%), 
                        url('{{ asset('images/bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        form {
            max-width: 420px;
            margin: 2% auto;
            padding: 30px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #fff;
            font-size: 28px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #e0f7fa;
            font-size: 15px;
        }

        input, select {
            width: 100%;
            padding: 14px 12px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #29a3a3;
            box-shadow: 0 0 0 2px rgba(41,163,163,0.2);
            background: white;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #29a3a3, #1d7a8c);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 10px;
        }

        button:hover {
            background: linear-gradient(to right, #218f8f, #166979);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            background-color: rgba(255, 224, 224, 0.8);
            color: #c0392b;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            border-left: 4px solid #c0392b;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #a5dff9;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #fff;
            text-decoration: underline;
        }

        header {
            width: 100%;
            height: 20vh;
            background: linear-gradient(135deg, #166979 0%, #0d4554 100%);
            display: flex;
            align-items: center;
            padding: 0 5%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        header h1 {
            color: white;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin-left: 20px;
            font-size: 24px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            form {
                margin: 5% auto;
                width: 90%;
            }
            
            header {
                height: 15vh;
                padding: 0 3%;
            }
            
            header h1 {
                font-size: 20px;
            }
            
            header img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ asset('images/icon.jpg') }}" alt="USP Logo">  
        <h1>The University of The South Pacific</h1>  
    </header>

    <form method="POST" action="/login">
        @csrf

        <h2>USP Portal Login</h2>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <label for="login_as">Login As</label>
        <select name="login_as" id="login_as" required onchange="toggleRegisterLink()">
            <option value="">-- Select Role --</option>
            <option value="employee">Employee</option>
            <option value="student">Student</option>
        </select>

        <label for="identifier">Employee/Student ID</label>
        <input type="text" name="identifier" id="identifier" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>

        <div class="register-link" id="registerLink" style="display:none;">
            <a href="{{ route('student.register') }}">Don't have an account? Enroll here</a>
        </div>
    </form>

    <script>
        function toggleRegisterLink() {
            const role = document.getElementById('login_as').value;
            document.getElementById('registerLink').style.display = (role === 'student') ? 'block' : 'none';
        }

        // Show register link if role was preselected
        window.onload = toggleRegisterLink;
    </script>
</body>
</html>