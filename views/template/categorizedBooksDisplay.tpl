<!-- Main section header -->
<section class="home section">
    <div class="container">
        <div class="book-section__header">
            <h2 class="book-section__title">{$section_title}</h2>
        </div>

        <!-- Add Statistics Summary -->
        <div class="category-stats">
            <div class="reading-stats__container container">
                <h2 class="reading-stats__title">
                    <i class="ri-bar-chart-line"></i> Statistics
                </h2>
                <div class="reading-stats__boxes">
                    <div class="stats-box" onclick="scrollToSection('toread')" style="cursor: pointer;">
                        <h3 class="stats-box__number"><?php echo isset($toReadBooks) ? count($toReadBooks) : 0; ?></h3>
                        <p class="stats-box__text">WANT TO<br>READ</p>
                    </div>
                    
                    <div class="stats-box" onclick="scrollToSection('owned')" style="cursor: pointer;">
                        <h3 class="stats-box__number"><?php echo isset($ownedBooks) ? count($ownedBooks) : 0; ?></h3>
                        <p class="stats-box__text">OWNED<br>BOOKS</p>
                    </div>
                    
                    <div class="stats-box" onclick="scrollToSection('read')" style="cursor: pointer;">
                        <h3 class="stats-box__number"><?php echo isset($readBooks) ? count($readBooks) : 0; ?></h3>
                        <p class="stats-box__text">BOOKS<br>READ</p>
                    </div>
                    
                    <div class="stats-box" style="cursor: pointer;">
                        <h3 class="stats-box__number"><?php 
                            $total = 
                                (isset($toReadBooks) ? count($toReadBooks) : 0) + 
                                (isset($ownedBooks) ? count($ownedBooks) : 0); 
                            echo $total;
                        ?></h3>
                        <p class="stats-box__text">TOTAL<br>BOOKS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<?php if (empty($ownedBooks) && empty($readBooks) && empty($toReadBooks)): ?>
    <section class="book-section section">
        <div class="container">
            <p class="empty-message">{$empty_message}</p>
        </div>
    </section>
<?php else: ?>

    <!-- To Read Books section -->
    <?php if (!empty($toReadBooks)): ?>
    <section class="book-section section" id="toread">
        <div class="container">
            <div class="book-section__header">
                <h2 class="book-section__title">To Read Pile (<?php echo count($toReadBooks); ?>)</h2>
            </div>
            
            <div class="book-section__content">
                <div class="book-grid">
                    <?php foreach ($toReadBooks as $book): ?>
                        <div class="book-item">
                            <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                    alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                    class="book-item__img">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Owned Books section -->
    <?php if (!empty($ownedBooks)): ?>
    <section class="book-section section" id="owned">
        <div class="container">
            <div class="book-section__header">
                <h2 class="book-section__title">Owned Books (<?php echo count($ownedBooks); ?>)</h2>
            </div>
            
            <div class="book-section__content">
                <div class="book-grid">
                    <?php foreach ($ownedBooks as $book): ?>
                        <div class="book-item">
                            <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                    alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                    class="book-item__img">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Read Books section -->
    <?php if (!empty($readBooks)): ?>
    <section class="book-section section" id="read">
        <div class="container">
            <div class="book-section__header">
                <h2 class="book-section__title">Read Books (<?php echo count($readBooks); ?>)</h2>
            </div>
            
            <div class="book-section__content">
                <div class="book-grid">
                    <?php foreach ($readBooks as $book): ?>
                        <div class="book-item">
                            <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                <img src="<?php echo $book['COVER_URL'] ?: 'assets/img/default-book.png'; ?>" 
                                    alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                    class="book-item__img">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>