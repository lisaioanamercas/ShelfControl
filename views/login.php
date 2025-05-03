<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Login - Shelf Control</title>
    <style>
        body {
            background-color: black;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        form {
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px white;
        }

        input {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        button {
            background-color: white;
            color: black;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Login to Shelf Control</h2>

    <form method="POST" action="">
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Utilizator" required />
        <input type="password" name="password" placeholder="Parolă" required />
        <button type="submit">Autentificare</button>
    </form>

</body>
</html>
