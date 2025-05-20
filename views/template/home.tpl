<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ==================== LOGO ============================== -->
        <link rel="shortcut icon" href="assets/img/favicon.png"  type="image/png">

        <!-- ====================ICONITE (remixincon) =========================== -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

        <!-- ==================== CSS =========================== -->
        <link rel="stylesheet" href="/ShelfControl/views/css/style.css">
        <link rel="stylesheet" href="/ShelfControl/views/css/lib.css">


        <title>ShelfControl - Explore</title>
    </head>
    <body>
        <!-- =========================== HEADER ============================== -->
         <header class="header shadow-header" id="header">
            <nav class="nav container">
                <a href="#" class="nav__logo">
                    <i class="ri-book-open-fill"></i> ShelfControl
                </a>

                <div class="nav__menu">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a href="index.html" class="nav__link">
                                <i class="ri-home-2-line"></i> 
                                <span> Home </span>
                            </a>
                        </li>
                        <!-- ======================================================= item delimit ==================================== -->
                        <li class="nav__item">
                            <a href="" class="nav__link">
                                <i class="ri-bookmark-line"></i>
                                <span> toRead </span>
                            </a>
                        </li>

                        <!-- ======================================================= item delimit ==================================== -->

                        <li class="nav__item">
                            <a href="#library" class="nav__link active-link">
                                <i class="ri-book-line"></i>
                                <span>Library</span>
                            </a>
                        </li>

                        <!-- ======================================================= item delimit ==================================== -->

                        <li class="nav__item">
                            <a href="#review" class="nav__link">
                                <i class="ri-chat-smile-3-line"></i>
                                <span>News</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="nav__actions">
                    <!-- Search button -->
                     <i class="ri-search-line search-button " id="search-button"></i>

                    <!-- User profile -->
                     <i class="ri-user-line profile-button" id = "profile-button"></i>

                     <!-- Theme button -->
                      <i class="ri-moon-line  change-teheme" id="theme-button"></i>
                </div>
            </nav>
         </header>

         <!-- ========================== SEARCH =============================== -->
          <div class="search" id="search-content">
           <form action="" method="GET" class="search__form" autocomplete="off">
            <i class="ri-search-line search__icon"></i>
            <input type="search" name="query" placeholder="Ce carte doresti sa cauti?" class="search__input">
        </form>

            <i class="ri-close-line search__close" id="search-close"></i>
          </div>
          
          <!-- ========================== PROFILE ============================== -->
           <div class="profile grid" id="profile-content">
                <form action="" class="profile__form">
                    <h3 class="profile__title"> Profile </h3>

                    <div class="profile__group grid">
                        <div>
                            <label for="profile-username" class="profile__label">username</label>
                            <input type="username" id="profile-username" class="profile__info">
                        </div>

                        <div>
                            <label for="profile-email" class="profile__label">email</label>
                            <input type="email" id="profile-email" class="profile__info">
                        </div>
                    </div>
                </form>

                <i class="ri-close-line profile__close" id="profile-close"></i>
           </div>

        <!-- ========================== MAIN ============================== -->
         <main class="main">
            <!-- =================== LIBRARY HEADER ================================= -->
             <section class="library-header section" id="library">
                <div class="library-header__container container">
                    <h1 class="library-header__title">Explore</h1>
                    <p class="library-header__description">
                        Explore your personal collection of books and discover new reads.
                    </p>
                        <div class="library-filter">
                        <button class="library-filter__btn active">All Books</button>
                        <button class="library-filter__btn">Fiction</button>
                        <button class="library-filter__btn">Non-Fiction</button>
                        <button class="library-filter__btn">Classics</button>
                    </div>
                </div>
             </section>

            <!-- =================== BOOK LIBRARY ================================= -->
            <section class="book-library section">
               <div class="book-library__container container grid" id="books-container">
                   <!-- Cărțile vor fi generate aici -->
               </div>
            </section>
         </main>

         <!-- ==================== FOOTER =========================== -->
         <footer class="footer">
            <div class="footer__container container">
                <p class="footer__copy">
                    &copy; 2025 ShelfControl. All rights reserved.
                </p>
            </div>
         </footer>

        <!-- ==================== JAVASCRIPT =========================== -->
        <script src="/ShelfControl/views/scripts/home.js"></script>
        <script src="/ShelfControl/views/scripts/search.js"></script>

    </body>
</html>