<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShelfControl</title>
    <link href="https://fonts.googleapis.com/css2?family=Montagu+Slab:opsz,wght@16..144,100..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/base.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/login.css">


</head>
<body>
    <div class="login-container">
        <button class="theme-toggle" id="theme-button">
            <i class="fas fa-moon"></i>
        </button>
        
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-book"></i>
            </div>
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Sign in to your ShelfControl account</p>
        </div>

        <div id="message" class="message" style="display: none;">
            {$message}
        </div>

        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" class="form-input" placeholder="Email address" required>
                <i class="fas fa-envelope form-icon"></i>
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-input" placeholder="Password" required>
                <i class="fas fa-lock form-icon"></i>
            </div>

            <button type="submit" class="login-button">
                Sign In
            </button>
        </form>

        <div class="login-footer">
            <a href="/ShelfControl/register" class="register-link">
                Don't have an account? <span>Sign up</span>
            </a>
        </div>
    </div>

    <script src="/ShelfControl/views/scripts/darkTheme.js"></script>
</body>
</html>