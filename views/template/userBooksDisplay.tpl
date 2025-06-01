<!-- userBooksDisplay.tpl -->
<section class="book-section section">
    <div class="container">
        <div class="book-section__header">
            <h2 class="book-section__title"><?php echo $section_title; ?> (<?php echo count($books); ?>)</h2>
        </div>

        <div class="section-loader" id="userbooks-loader"></div>

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
