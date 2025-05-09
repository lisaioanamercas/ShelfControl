<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
        }

        form {
            background-color: #2a2a3b;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f0f0f0;
        }

        input {
            display: block;
            width: 100%;
            margin: 15px 0;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #333;
            color: #fff;
        }

        button {
            background-color: #007bff; /* Albastru */
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            font-weight: bold;
            border-radius: 8px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3; /* Albastru mai închis pentru hover */
        }

        .error {
            color: #ff4d4d;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Register</h2>
    <?php if (!empty($error)): ?>
       <div style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></div>
     <?php endif; ?>
    <?php if(!empty($success)):?>
        <div style="color: green; text-align: center;"><?= htmlspecialchars($success) ?></div>
     <?php endif; ?>



     
    <form method="POST" action="">

        <input type="text" name="email" placeholder="Email" required />
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        <button type="submit">Submit</button>
    </form>

</body>
</html>
