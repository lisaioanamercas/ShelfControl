<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ShelfControl/views/css/home.css">
</head>
<body>
    <div class="dashboard-container" style="max-width: 600px; margin: 40px auto; background: #fff; color: #222; border-radius: 10px; box-shadow: 0 2px 10px #0002; padding: 30px; text-align: center;">
        <h1>Bine ai venit, {$username}!</h1>
        <p>Te-ai autentificat cu succes în ShelfControl.</p>
        <div class="buttons" style="margin-top: 30px;">
            <a href="/ShelfControl/logout" class="register-link">Logout</a>
            <a href="/ShelfControl/profile" class="register-link">Profilul meu</a>
        </div>
    </div>
</body>
</html>