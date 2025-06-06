<!-- Main section header -->
<section class="home section">
    <div class="container">
        <div class="book-section__header">
            <h2 class="book-section__title"><?php echo $section_title; ?></h2>
        </div>

        <!-- Add Statistics Summary -->
        <div class="category-stats">
            <div class="reading-stats__container container">
                <h2 class="reading-stats__title">
                    <i class="ri-bar-chart-line"></i> Library Statistics
                </h2>
                <div class="reading-stats__boxes">
                    <div class="stats-box" onclick="scrollToSection('discover')" style="cursor: pointer;">
                        <h3 class="stats-box__number"><?php echo count($undiscoveredBooks); ?></h3>
                        <p class="stats-box__text">BOOKS TO<br>DISCOVER</p>
                    </div>
                    
                    <div class="stats-box" onclick="scrollToSection('all-books')" style="cursor: pointer;">
                        <h3 class="stats-box__number"><?php echo count($allBooks); ?></h3>
                        <p class="stats-box__text">TOTAL<br>BOOKS</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="search-container" style="margin-bottom: 2rem;">
            <form action="/ShelfControl/search" method="GET" class="library-filter">
                <input type="text" name="query" class="library-filter__input" placeholder="Search by title, author, genre..." required>
                <button type="submit" class="library-filter__btn">
                    <i class="ri-search-line"></i> Search
                </button>
            </form>
        </div>
    </div>
</section>

<?php if (empty($undiscoveredBooks) && empty($allBooks)): ?>
    <section class="book-section section">
        <div class="container">
            <p class="empty-message"><?php echo $empty_message; ?></p>
        </div>
    </section>
<?php else: ?>
    <!-- Books to Discover section -->
    <?php if (!empty($undiscoveredBooks)): ?>
    <section class="book-section section" id="discover">
        <div class="container">
            <div class="book-section__header">
                <h2 class="book-section__title">Books to Discover (<?php echo count($undiscoveredBooks); ?>)</h2>
            </div>
            
            <div class="book-library__container container grid">
                <?php foreach ($undiscoveredBooks as $book): ?>
                    <div class="book-card">
                        <div class="book-card__cover">
                            <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                 class="book-card__img">
                        </div>
                        <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>" class="book-card-link">
                            <div class="book-card__data">
                                <h3 class="book-card__title"><?php echo htmlspecialchars($book['TITLE']); ?></h3>
                                <p class="book-card__author"><?php echo htmlspecialchars($book['AUTHOR_NAME'] ?: 'Unknown Author'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- All Books section -->
    <?php if (!empty($allBooks)): ?>
    <section class="book-section section" id="all-books">
        <div class="container">
            <div class="book-section__header">
                <h2 class="book-section__title">All Books (<?php echo count($allBooks); ?>)</h2>
            </div>
            
            <div class="book-library__container container grid">
                <?php foreach ($allBooks as $book): ?>
                    <div class="book-card">
                        <div class="book-card__cover">
                            <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                 class="book-card__img">
                        </div>
                        <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>" class="book-card-link">
                            <div class="book-card__data">
                                <h3 class="book-card__title"><?php echo htmlspecialchars($book['TITLE']); ?></h3>
                                <p class="book-card__author"><?php echo htmlspecialchars($book['AUTHOR_NAME'] ?: 'Unknown Author'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>