<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ShelfControl/views/css/home.css">
</head>
<body>
   <ul>
   <li><a href="/ShelfControl/home">Home</a></li>
   <li><a href="/ShelfControl/home">Your books</a></li>
   <li><a href="/ShelfControl/home">About us</a></li>
   <li><a  href="/ShelfControl/logout">Account</a></li>
   <li>
        <form class="search-form" action="/ShelfControl/search" method="GET">
            <input type="search" id="site-search" name="q" placeholder="Search..." />
            <button type="submit">Search</button>
        </form>
    </li>
   </ul>

   <div id="script-container">
       <h2>Explore</h2>
       <div id="book-list"></div> <!-- This will hold the list of books -->
   </div>
   <script src="/ShelfControl/views/scripts/home.js"></script>
</body>
</html>
