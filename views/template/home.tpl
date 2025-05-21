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

        <title>ShelfControl</title>
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
                            <a href="#home" class="nav__link">
                                <i class="ri-home-2-line"></i> 
                                <span> Home </span>
                            </a>
                        </li>
                        <!-- ======================================================= item delimit ==================================== -->
                        <li class="nav__item">
                            <a href="#toRead" class="nav__link">
                                <i class="ri-bookmark-line"></i>
                                <span> toRead </span>
                            </a>
                        </li>

                        <!-- ======================================================= item delimit ==================================== -->

                        <li class="nav__item">
                            <a href="#new" class="nav__link">
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
            <form action="" class="search__form">
                <i class="ri-search-line search__icon"></i>
                <input type="search" placeholder="Ce carte doresti sa cauti?" class="search__input">
            </form>

            <i class="ri-close-line search__close" id="search-close"></i>
          </div>
          <!-- ========================== PROFILE  tralalero tralala ============================== -->
           <div class="profile grid" id="profile-content">
                <form action="" class="profile__form">
                    <h3 class="profile__title"> Profile </h3>

                    <div class="profile__group grid">
                        <!-- <div>
                            <label for="profile-username" class="profile__label">username</label>
                            <input type="username" id="profile-username" class="profile__info">
                        </div> -->
                        <!-- pe asta de sus nu stiu de ce trebuie sa o tin aici dar altfel nu imi apare deloc username daca o sterg? -->

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
            <!-- =================== HOME ================================= -->
             <section class="home section" id="home">
                <div class="home__container container grid">
                    <div class="home__data">
                        <h1 class="home__title">
                            Welcome to <br>
                            ShelfControl
                        </h1>

                        <p class="home__description">
                            Your all-in-one website for library management. Both local and nationwide.
                        </p>

                        <a href="/ShelfControl/explore" class="button">Explore</a>
                    </div>
                    
                    <!-- Current reading container -->
                    <!-- Current reading container -->
                    <div class="current-reads__container">
                        <h3 class="current-reads__title">Current Reads (<?php echo count($currentlyReading); ?>)</h3>
                        <div class="current-reads__books">
                            <?php if (empty($currentlyReading)): ?>
                                <p>You're not currently reading any books.</p>
                            <?php else: ?>
                                <?php foreach ($currentlyReading as $index => $book): ?>
                                    <?php 
                                        $totalPages = intval($book['PAGES']) ?: 1;
                                        $pagesRead = intval($book['PAGES_READ']) ?: 0;
                                        $percentage = min(100, round(($pagesRead / $totalPages) * 100)); 
                                    ?>
                                    <!-- Book Item -->
                                    <div class="current-reads__item" data-book-id="<?php echo $book['BOOK_ID']; ?>">
                                        <div class="current-reads__cover">
                                            <img src="<?php echo $book['COVER_URL'] ?: '/assets/img/default-book.png'; ?>" 
                                                alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                                class="current-reads__img">
                                        </div>
                                        <div class="current-reads__info">
                                            <h4 class="current-reads__book-title"><?php echo htmlspecialchars($book['TITLE']); ?></h4>
                                            <p class="current-reads__author"><?php echo htmlspecialchars($book['AUTHOR_NAME']); ?></p>
                                            <div class="current-reads__progress">
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: <?php echo $percentage; ?>%"></div>
                                                </div>
                                                <span class="progress-text"><?php echo $percentage; ?>%</span>
                                                <button class="edit-progress-btn" aria-label="Edit reading progress">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <div class="progress-editor" id="editor-<?php echo $index; ?>">
                                                    <div class="page-input-container">
                                                        <input type="number" class="page-input" value="<?php echo $pagesRead; ?>" 
                                                            min="0" max="<?php echo $totalPages; ?>">
                                                        <span class="page-separator">of</span>
                                                        <span class="total-pages"><?php echo $totalPages; ?></span>
                                                    </div>
                                                    <div class="editor-actions">
                                                        <button class="save-btn">Save</button>
                                                        <button class="finish-btn">Mark as finished</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
             </section>

            <!-- ==================SERVICII =============================== -->
            <!-- To Read Pile section -->
            <section class="book-section section" id="toread">
                <div class="container">
                    <div class="book-section__header">
                        <h2 class="book-section__title">To Read Pile</h2>
                        <a href="/ShelfControl/toread" class="book-section__link">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                    
                    <div class="book-section__content">
                        <div class="book-grid">
                            <?php if (empty($toReadBooks)): ?>
                                <p>No books in your to-read pile yet.</p>
                            <?php else: ?>
                                <?php foreach ($toReadBooks as $book): ?>
                                    <div class="book-item">
                                        <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                            <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                                alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                                class="book-item__img">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Owned Books section -->
            <section class="book-section section" id="owned">
                <div class="container">
                    <div class="book-section__header">
                        <h2 class="book-section__title">Owned Books</h2>
                        <a href="/ShelfControl/library" class="book-section__link">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                    
                    <div class="book-section__content">
                        <div class="book-grid">
                            <?php if (empty($ownedBooks)): ?>
                                <p>No owned books yet.</p>
                            <?php else: ?>
                                <?php foreach ($ownedBooks as $book): ?>
                                    <div class="book-item">
                                        <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                            <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                                alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                                class="book-item__img">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
         </main>         
         <!-- ==================== JAVASCRIPT =========================== -->
            <script src="/ShelfControl/views/scripts/home.js"></script>
            <script src="/ShelfControl/views/scripts/darkTheme.js"></script>

    </body>
</html>
