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
                                <!-- Add this anchor tag around the image -->
                                <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                    <img src="<?php echo $book['COVER_URL'] ?: '/assets/img/default-book.png'; ?>" 
                                        alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                        class="current-reads__img">
                                </a>
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
                <h2 class="book-section__title">To Read Pile (<?php echo count($toReadBooks); ?>)</h2>
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
                <h2 class="book-section__title">Owned Books (<?php echo count($ownedBooks); ?>)</h2>
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

    <!-- Read Books section -->
    <section class="book-section section" id="read">
        <div class="container">
            <div class="book-section__header">
                <h2 class="book-section__title">Read Books (<?php echo count($readBooks); ?>)</h2>
                <a href="/ShelfControl/read" class="book-section__link">
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            
            <div class="book-section__content">
                <div class="book-grid">
                    <?php if (empty($readBooks)): ?>
                        <p>You haven't read any books yet.</p>
                    <?php else: ?>
                        <?php foreach ($readBooks as $book): ?>
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

 