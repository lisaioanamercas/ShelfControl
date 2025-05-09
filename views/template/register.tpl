<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/ShelfControl/views/css/register.css">
</head>
<body>

    <h2>{$heading}</h2>

    <div id="message" style="text-align: center; margin-top: 20px;">
        {$message}
    </div>

    <form method="POST" action="">
        <input type="text" name="email" placeholder="Email" required />
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        <button type="submit">Submit</button>
    </form>
    <a href="/ShelfControl/login" class="register-link">I already have an account</a>

</body>
</html>