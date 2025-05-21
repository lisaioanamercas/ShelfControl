<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$book_title}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS & Icon imports -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/style.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/book.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/bookPage/book-info.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/bookPage/dark-theme-book.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/bookPage/reading-progress.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/bookPage/similar-books.css">

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


    <!-- Main content -->
    <main class="main">
        <section class="book-details section">
            <div class="book-details__container container grid">
                <!-- Book Cover Column -->
                <div class="book-details__cover-container">
                    <div class="book-cover">
                        <img src="{$book_image_url}" alt="{$book_title}" class="book-cover__img">
                    </div>
                    <div class="book-actions">
                        <!-- Status dropdown -->
                        <div class="status-dropdown">
                            <button class="status-btn" id="statusBtn">
                                <span id="currentStatus"><?php echo $reading_status; ?></span>
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                            <div class="status-options" id="statusOptions">
                                <div class="status-option" data-status="to-read">to-read</div>
                                <div class="status-option" data-status="reading">reading</div>
                                <div class="status-option" data-status="completed">completed</div>
                                <div class="status-option" data-status="dnf">dnf</div>
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="book-actions__buttons">
                            <button class="book-action__btn owned-btn <?php echo $is_owned ? 'active' : ''; ?>" id="ownedBtn" data-book-id="{$book_id}">
                                <i class="ri-bookmark-line"></i> <span>owned</span>
                            </button>
                            <button class="book-action__btn buy-btn" id="buyBtn">
                                <i class="ri-shopping-cart-line"></i> <span>buy</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Book Info Column -->
                <div class="book-details__info">
                    <h1 class="book-title">{$book_title}</h1>
                    <h2 class="book-author">{$book_author}</h2>
                    
                    <!-- Book metadata -->
                    <div class="book-meta">
                        <div class="book-meta__item">
                            <span class="book-meta__label">Publication:</span>
                            <span class="book-meta__value">{$book_publication_year}</span>
                        </div>
                        <div class="book-meta__item">
                            <span class="book-meta__label">Pages:</span>
                            <span class="book-meta__value">{$book_page_count}</span>
                        </div>
                        <div class="book-meta__item">
                            <span class="book-meta__label">Language:</span>
                            <span class="book-meta__value">{$book_language}</span>
                        </div>
                        <div class="book-meta__item">
                            <span class="book-meta__label">ISBN:</span>
                            <span class="book-meta__value">{$book_isbn}</span>
                        </div>
                        <div class="book-meta__item">
                            <span class="book-meta__label">Publisher:</span>
                            <span class="book-meta__value">{$book_publisher}</span>
                        </div>
                        <?php if ($book_sub_publisher != 'N/A'): ?>
                        <div class="book-meta__item">
                            <span class="book-meta__label">Sub-publisher:</span>
                            <span class="book-meta__value">{$book_sub_publisher}</span>
                        </div>
                        <?php endif; ?>
                        <?php if ($book_translator != 'N/A'): ?>
                        <div class="book-meta__item">
                            <span class="book-meta__label">Translator:</span>
                            <span class="book-meta__value">{$book_translator}</span>
                        </div>
                        <?php endif; ?>
                        <?php if ($book_source_api != 'N/A'): ?>
                        <div class="book-meta__item">
                            <span class="book-meta__label">Source:</span>
                            <span class="book-meta__value">{$book_source_api}</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="book-summary">
                        <h3 class="book-summary__title">Description</h3>
                        <div class="book-summary__content" id="bookSummary">
                            <p><?php echo $book_description; ?></p>
                        </div>
                    </div>
                    
                    <!-- Reading progress section (visible when status is "reading") -->
                    <div class="reading-progress <?php echo ($reading_status == 'reading') ? 'active' : ''; ?>" id="readingProgress">
                        <h3 class="reading-progress__title">Reading Progress</h3>
                        <div class="reading-progress__bar">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill" style="width: <?php echo ($book_page_count > 0) ? round(($pages_read / $book_page_count) * 100) : 0; ?>%"></div>
                            </div>
                            <span class="progress-text" id="progressText"><?php echo ($book_page_count > 0) ? round(($pages_read / $book_page_count) * 100) : 0; ?>%</span>
                        </div>
                        <div class="page-tracking">
                            <div class="page-input-container">
                                <input type="number" class="page-input" id="currentPageInput" value="<?php echo $pages_read; ?>" min="0" max="<?php echo $book_page_count; ?>">
                                <span class="page-separator">of</span>
                                <span class="total-pages" id="totalPages"><?php echo $book_page_count; ?></span>
                            </div>
                            <button class="save-progress-btn" id="saveProgressBtn" data-book-id="{$book_id}">Update Progress</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- JavaScript -->
    <script src="./views/scripts/book.js"></script>
    <script src="./views/scripts/home.js"></script>
    <script src="./views/scripts/darkTheme.js"></script>
    <script src="./views/scripts/search_db.js"></script>


</body>
</html>
