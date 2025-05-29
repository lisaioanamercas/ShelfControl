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
            <!-- <h2 class="book-author">{$book_author}</h2> -->
            <div class="book-meta__item">
                <h2 span class="book-meta__label">
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/author/<?php echo urlencode($book_author); ?>" class="metadata-link">{$book_author}</a>
                    </span>
                </h2>
            </div>
            
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
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/publisher/<?php echo urlencode($book_publisher); ?>" class="metadata-link">{$book_publisher}</a>
                    </span>
                </div>

                <?php if ($book_sub_publisher != 'N/A'): ?>
                <div class="book-meta__item">
                    <span class="book-meta__label">Sub-publisher:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/subpublisher/<?php echo urlencode($book_sub_publisher); ?>" class="metadata-link">{$book_sub_publisher}</a>
                    </span>
                </div>
                <?php endif; ?>

                <?php if ($book_translator != 'N/A'): ?>
                <div class="book-meta__item">
                    <span class="book-meta__label">Translator:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/translator/<?php echo urlencode($book_translator); ?>" class="metadata-link">{$book_translator}</a>
                    </span>
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
                    <div class="book-summary__content">
                        <p><?php echo $book_description; ?></p>
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


            <!-- Similar Books - MOVED HERE !!!!!!!!!! -->
            <div class="similar-books">
                <h3 class="similar-books__title">You might also like</h3>
                <div class="similar-books__grid" id="suggestionsGrid">
                    <!-- The content will be dynamically populated by book.js -->
                </div>
            </div>
        </div>
        <!-- SECTIUNE PENTRU REVIEW URI -->

        <div class="book-reviews">
            <h3 class="book-reviews__title">Reviews</h3>
                <button class="write-review-btn" id="writeReviewBtn">
                    <i class="ri-edit-line"></i> Write a Review
                </button>
            <div class="reviews-list" id="reviewsList">
                <!-- Reviews will be loaded here -->
            </div>
        </div>
    
        <div class="review-form-overlay" id="reviewFormOverlay">
            <div class="review-form-container">
                <div class="review-form-header">
                    <h3>Write a Review</h3>
                    <button class="close-review-form" id="closeReviewForm">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <form class="review-form" id="reviewForm">
                    <div class="star-rating">
                        <p>Your Rating:</p>
                        <div class="stars">
                            <i class="ri-star-line star" data-value="1"></i>
                            <i class="ri-star-line star" data-value="2"></i>
                            <i class="ri-star-line star" data-value="3"></i>
                            <i class="ri-star-line star" data-value="4"></i>
                            <i class="ri-star-line star" data-value="5"></i>
                            <input type="hidden" id="ratingValue" name="rating" value="0">
                        </div>
                    </div>
                    <div class="review-text">
                        <label for="reviewContent">Your Review:</label>
                        <textarea id="reviewContent" name="reviewContent" rows="5" placeholder="Share your thoughts about this book..."></textarea>
                    </div>
                    <button type="submit" class="submit-review-btn">Submit Review</button>
                </form>
            </div>
        </div>
    </div> 
</div>
