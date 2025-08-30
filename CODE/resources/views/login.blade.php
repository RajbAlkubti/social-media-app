<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="card">
        <div class="logo">𝕐</div>
        <h1>Sign in to Y</h1>
        
        <form method='POST' action='/login'>
        @csrf
            <input type="text" name = 'email' placeholder="Email" required>
            <input type="password" name = 'password' placeholder="Password" required>
            <button type="submit" class="submit-btn">Sign in</button>
        </form>
        
        <div class="footer-link">
            Don't have an account? <a href="/register">Sign up</a>
        </div>
    </div>
</body>
</html>