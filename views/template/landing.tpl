<!DOCTYPE html>
<html>
<head>
    <title>{$title}</title>
    <link rel="stylesheet" href="/ShelfControl/views/css/landing.css">
   <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
</head>
<body>
    <div class="left-title">
        <h1>{$heading}</h1>
        <p>{$subheading}</p>
        <div class="buttons">
            <button onclick="window.location.href='{$loginUrl}'">Login</button>
            <a href="{$registerUrl}" class="register-link">Register</a>
        </div>
    </div>
    <img class="right-image" src="{$imagePath}" alt="Shelf Control Image">
</body>
</html>
