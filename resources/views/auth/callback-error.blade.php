<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Error</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        .error-icon {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        p {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            font-family: monospace;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠️</div>
        <h1>Authentication Error</h1>
        <p>{{ $message }}</p>
        
        <div class="details">
            <strong>What happened?</strong><br>
            You tried to access the OAuth callback URL directly, but this endpoint is only meant to be accessed as part of a complete OAuth authentication flow from WorkOS.
        </div>
        
        <div class="details">
            <strong>How to fix this:</strong><br>
            1. Go to the login page<br>
            2. Click the login button<br>
            3. Complete the authentication with WorkOS<br>
            4. You'll be redirected back automatically
        </div>
        
        <a href="{{ $login_url }}" class="btn">Go to Login Page</a>
    </div>
</body>
</html> 