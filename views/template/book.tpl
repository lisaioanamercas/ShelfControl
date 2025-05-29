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
                        <p><?php echo substr($book_description, 0, 300); ?>...</p>
                        <!-- Store the full description in a hidden element -->
                        <p class="full-description" style="display: none;"><?php echo $book_description; ?></p>
                        <a href="#" class="read-more-btn" id="readMoreBtn">Read more</a>
                    </div>
                
                <!-- Similar Books - MOVED HERE -->
                <div class="similar-books">
                    <h3 class="similar-books__title">You might also like</h3>
                    <div class="similar-books__grid" id="suggestionsGrid">
                        <!-- The content will be dynamically populated by book.js -->
                    </div>
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
