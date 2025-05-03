<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Shelf Control</title>
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
            }
            button {
                background-color: white;
                color: black;
                border: none;
                padding: 10px 20px;
                margin: 10px;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
            }
            button:hover {
                background-color: gray;
                color: white;
            }
        </style>
    </head>
    <body>
        <h1>Welcome to Shelf Control</h1>
        <button onclick="window.location.href='/Shelf%20Control/login'">Login</button>
        <button onclick="window.location.href='/register'">Register</button>
    </body>
</html>