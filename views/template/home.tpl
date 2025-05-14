<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="views/css/home.css"> <!-- Conectează home.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="topnav">
  <a href="logo.png"><img src="views/resources/logo6.svg" alt="Logo" width="40" height="40"></a></li>
  <a href="#home">Home</a>
  <a href="#about">About</a>
  <a href="#news">Account</a>
  <div class="search-container">
    <form action="/action_page.php">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
</div>
  <div class="dropdown">
    <button onclick="myFunction()" class="dropbtn">Filter</button>
    <div id="myDropdown" class="dropdown-content">
      <a href="#">Author</a>
      <a href="#">Publisher</a>
      <a href="#">Genre</a>
    </div>
    </div>
 <div id="book-list"></div>

<!-- Conectează home.js -->
<script src="views/scripts/home.js"></script>
</body>
</html>
