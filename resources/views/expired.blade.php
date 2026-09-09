<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Suspended</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; color: #333; text-align: center; padding: 100px 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 50px 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; margin-bottom: 20px; font-size: 32px; }
        p { font-size: 18px; line-height: 1.6; margin-bottom: 20px; color: #555; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 25px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; transition: background 0.3s; }
        .btn:hover { background-color: #0056b3; }
        .icon { font-size: 60px; color: #dc3545; margin-bottom: 20px; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <i class="fa fa-exclamation-triangle icon"></i>
        <h1>Subscription Expired</h1>
        <p>Your system subscription expired on <strong>Sep 10, 2026</strong>.</p>
        <p>The system has been suspended automatically. Please contact your administrator or renew your subscription to restore access.</p>
        
        <form id="logout-form" action="{{ route('logout') ?? url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <a href="{{ url('/login') }}" class="btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout / Return to Login</a>
    </div>
</body>
</html>
