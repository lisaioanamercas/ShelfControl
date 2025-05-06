<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Shelf Control</title>
    <style>
        body {
            background-color:rgb(182, 34, 34);
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center; 
            align-items: center; 
        }

        .left-title {
            display: flex;
            flex-direction: column; /* Asigură-te că sunt pe linii separate */
            align-items: flex-start; /* Aliniere la stânga */
            justify-content: flex-start; /* Aliniere sus */
            padding-right: 90px;
            
        }

        .left-title h1 {
            font-size: 50px;
            margin: 0;
        }

        .left-title p {
            font-size: 30px;
            margin-top: 5px; /* spațiu între titlu și text */
        }

        .right-image{

            width: 600px; /* sau orice valoare vrei, de ex. 250px, 200px etc. */
            height: 800px;
            margin-left: 40px;
            margin-right: 70px; 
        }
       
        .buttons {
            display: flex;
             gap: 15px;
            margin-top: 5px; /* redus de la 20px la 5px */
}

        button {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .register-link{
           
            background-color:rgb(182, 34, 34);
            color: white;
            padding: 10px 0; 
            display: inline-block;
            cursor: pointer;
        }

        button:hover {
            background-color:grey;
            color: white;
        }

</style>

</head>
<body>
    <div class="left-title">
        <h1>Shelf Control</h1>
        <p>Cause you are one book away from a book avalanche</p>
        <div class="buttons">
          <button onclick="window.location.href='/Shelf%20Control/login'">Login</button>
          <a href="/register" class="register-link">Register</a>
        </div>
    </div>
    <img class="right-image" src="models\landingPictures.jpg" alt="Shelf Control Image">
</body>
</html>
