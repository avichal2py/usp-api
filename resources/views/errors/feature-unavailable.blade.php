<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            position: relative;
            overflow: hidden;
        }
        
        h2 {
            color: #d32f2f;
            margin-bottom: 20px;
            font-weight: 500;
            position: relative;
        }
        
        h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #d32f2f;
            animation: underlineGrow 0.8s ease-out;
        }
        
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0;
            animation: fadeIn 0.8s ease-out 0.4s forwards;
        }
        
        .icon {
            font-size: 60px;
            color: #d32f2f;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounce 1s ease infinite alternate;
        }
        
        .progress-container {
            height: 6px;
            background: #f1f1f1;
            border-radius: 3px;
            margin: 30px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #d32f2f, #f44336);
            border-radius: 3px;
            animation: progressLoad 2s ease-in-out infinite;
        }
        
        .contact {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
            opacity: 0;
            animation: fadeIn 0.8s ease-out 0.8s forwards;
        }
        
        @keyframes underlineGrow {
            from { width: 0; }
            to { width: 60px; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }
        
        @keyframes progressLoad {
            0% { width: 0; margin-left: 0; }
            50% { width: 100%; margin-left: 0; }
            100% { width: 0; margin-left: 100%; }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container pulse">
        <div class="icon">⚠️</div>
        <h2>This feature is currently unavailable</h2>
        <p>Our PDF generation service is temporarily down for maintenance. Our engineering team is working to restore service as quickly as possible.</p>
        
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
        
        <p>We apologize for the inconvenience and appreciate your patience.</p>
        <div class="contact">For urgent requests, please contact <a href="#">support@company.com</a></div>
    </div>
</body>
</html>