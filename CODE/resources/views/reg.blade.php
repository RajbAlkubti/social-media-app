<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name='csrf-token' content="{{ csrf_token() }}" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="card">
        <div class="logo">𝕐</div>
        <h1>Sign up for Y</h1>
        
        <form method="POST" action="/register">
        
        @csrf

        <input type="text" name="name" placeholder="Full Name" required id="name">
        <input type="text" name="username" placeholder="Username" required id="username">
        <input type="email" name="email" placeholder="Email" required id="email">
        <input type="password" name="password" placeholder="Password" required id="password">
        

    <button type="submit" class="submit-btn">Sign up</button>
        </form>
        @if ($errors->any())
            <div class="text-red-600 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="footer-link">
            Already have an account? <a href="/login">Sign in</a>
        </div>
    </div>
</body>
</html>