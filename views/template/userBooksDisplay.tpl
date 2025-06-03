<!-- userBooksDisplay.tpl -->
<section class="book-section section">
    <div class="container">
        <div class="book-section__header">
            <h2 class="book-section__title"><?php echo $section_title; ?> (<?php echo count($books); ?>)</h2>
        </div>

        <div class="section-loader" id="userbooks-loader"></div>

        <?php if (isset($showSearchForm) && $showSearchForm): ?>
            <div class="search-db-container">
                <form id="db-search-form" class="db-search-form">
                    <input type="text" id="db-search-input" class="db-search-input" placeholder="Search books..." required>
                    <button type="submit" class="db-search-btn">
                        <i class="ri-search-line"></i>
                    </button>
                </form>
            </div>
            <div id="search-results-container" class="search-results-container" style="display: none;"></div>
        <?php endif; ?>



        <div class="book-section__content" id="userbooks-content" style="display: none;">
            <div class="book-grid">
                <?php if (empty($books)): ?>
                    <p><?php echo $empty_message; ?></p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
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
