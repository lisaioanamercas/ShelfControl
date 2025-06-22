<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShelfControl</title>
    <link href="https://fonts.googleapis.com/css2?family=Montagu+Slab:opsz,wght@16..144,100..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/base.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/register.css">

</head>
<body>
    <div class="register-container">
        <button class="theme-toggle" id="theme-button">
            <i class="fas fa-moon"></i>
        </button>
        
        <div class="register-header">
            <div class="register-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="register-title">Register</h2>
            <p class="register-subtitle">Create your ShelfControl account</p>
        </div>

        <div id="message" class="message" style="display: <?= !empty($message) ? 'block' : 'none'; ?>;">
                <?= $message ?? '' ?>
            </div>        <form class="register-form" id="registerForm" method="POST">
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-input" placeholder="Email" required>
                <i class="fas fa-envelope form-icon"></i>
            </div>

            <div class="form-group">
                <input type="text" name="username" id="username" class="form-input" placeholder="Username" required>
                <i class="fas fa-user form-icon"></i>
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" class="form-input" placeholder="Password" required>
                <i class="fas fa-lock form-icon"></i>
            </div>

            <div class="form-group">
                <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="Confirm Password" required>
                <i class="fas fa-lock form-icon"></i>
            </div>            
            <div class="form-group">
                <input type="text" name="city" id="city" class="form-input" placeholder="City" required>
                <i class="fas fa-city form-icon"></i>
            </div>

            <button type="submit" class="register-button" id="submitBtn">
                Submit
            </button>
        </form>

        <footer class="register-footer">
            <a href="/ShelfControl/login" class="login-link">
                I already have an account
            </a>
        </footer>
    </div>

    <script src="/ShelfControl/views/scripts/darkTheme.js"></script>
</body>
</html>