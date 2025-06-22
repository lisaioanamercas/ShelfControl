<section class="book-details section">
    <div class="book-details__container container grid">
        <!-- Book Cover Column -->
        <div class="book-details__cover-container">
            <div class="book-cover">
                <img src="<?php echo htmlspecialchars($book_image_url); ?>" alt="<?php echo htmlspecialchars($book_title); ?>" class="book-cover__img">
            </div>

            <div class="book-actions">
                <!-- Status dropdown -->
                <div class="status-dropdown">
                    <button class="status-btn" id="statusBtn">
                        <span id="currentStatus"><?php echo htmlspecialchars($reading_status); ?></span>
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="status-options" id="statusOptions">
                        <div class="status-option" data-status="to-read">to-read</div>
                        <div class="status-option" data-status="reading">reading</div>
                        <div class="status-option" data-status="completed">completed</div>
                        <div class="status-option" data-status="dnf">dnf</div>
                        <div class="status-option group-reading" data-status="group-reading">group reading</div>
                    </div>
                </div>
                <div class="book-actions__buttons">
                    <button class="book-action__btn owned-btn <?php echo $is_owned ? 'active' : ''; ?>" id="ownedBtn" data-book-id="<?php echo htmlspecialchars($book_id); ?>">
                        <i class="ri-bookmark-line"></i> <span>owned</span>
                    </button>
                    <button class="book-action__btn buy-btn" id="buyBtn">
                        <i class="ri-git-repository-line"></i> <span>library</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Book Info Column -->
        <div class="book-details__info">
            <div class="book-title-header">
                <h1 class="book-title">
                    <a href="/ShelfControl/books/edition/<?php echo urlencode($book_title); ?>" class="metadata-link"><?php echo htmlspecialchars($book_title); ?></a>
                </h1>
                
                <!-- BUTOANE PENTRU ADMIN - Top right position -->
                <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                    <div class="admin-actions">
                        <button class="admin-action__btn edit-book-btn" id="editBookBtn" data-book-id="<?php echo htmlspecialchars($book_id); ?>" title="Edit Book">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="admin-action__btn delete-book-btn" id="deleteBookBtn" data-book-id="<?php echo htmlspecialchars($book_id); ?>" title="Delete Book">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
                        
            <h2 class="book-author" data-author-name="<?php echo htmlspecialchars($book_author); ?>">
                <a href="/ShelfControl/books/author/<?php echo urlencode($book_author); ?>" class="metadata-link"><?php echo htmlspecialchars($book_author); ?></a>
            </h2> 

            <!-- Book metadata -->
            <div class="book-meta">
                <div class="book-meta__item">
                    <span class="book-meta__label">Publication:</span>
                    <span class="book-meta__value"><?php echo htmlspecialchars($book_publication_year); ?></span>
                </div>
                <div class="book-meta__item">
                    <span class="book-meta__label">Pages:</span>
                    <span class="book-meta__value"><?php echo htmlspecialchars($book_page_count); ?></span>
                </div>
                <div class="book-meta__item">
                    <span class="book-meta__label">Language:</span>
                    <span class="book-meta__value"><?php echo htmlspecialchars($book_language); ?></span>
                </div>
                <div class="book-meta__item">
                    <span class="book-meta__label">ISBN:</span>
                    <span class="book-meta__value"><?php echo htmlspecialchars($book_isbn); ?></span>
                </div>
                <div class="book-meta__item">
                    <span class="book-meta__label">Genre:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/genre/<?php echo urlencode($book_genre); ?>" class="metadata-link"><?php echo htmlspecialchars($book_genre); ?></a>
                    </span>
                </div>
                <div class="book-meta__item">
                    <span class="book-meta__label">Publisher:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/publisher/<?php echo urlencode($book_publisher); ?>" class="metadata-link"><?php echo htmlspecialchars($book_publisher); ?></a>
                    </span>
                </div>

                <?php if (isset($book_sub_publisher) && $book_sub_publisher !== 'N/A' && !empty($book_sub_publisher)): ?>
                <div class="book-meta__item">
                    <span class="book-meta__label">Sub-publisher:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/subpublisher/<?php echo urlencode($book_sub_publisher); ?>" class="metadata-link"><?php echo htmlspecialchars($book_sub_publisher); ?></a>
                    </span>
                </div>
                <?php endif; ?>

                <?php if (isset($book_translator) && $book_translator !== 'N/A' && !empty($book_translator)): ?>
                <div class="book-meta__item">
                    <span class="book-meta__label">Translator:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/translator/<?php echo urlencode($book_translator); ?>" class="metadata-link"><?php echo htmlspecialchars($book_translator); ?></a>
                    </span>
                </div>
                <?php endif; ?>

                <?php if (isset($book_source_api) && $book_source_api !== 'N/A' && !empty($book_source_api)): ?>
                <div class="book-meta__item">
                    <span class="book-meta__label">Source:</span>
                    <span class="book-meta__value"><?php echo htmlspecialchars($book_source_api); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="book-summary">
                <h3 class="book-summary__title">Description</h3>
                <div class="book-summary__content" id="bookSummary">
                    <p><?php echo htmlspecialchars(substr($book_description, 0, 300)); ?>...</p>
                    <!-- Store the full description in a hidden element -->
                    <p class="full-description" style="display: none;"><?php echo htmlspecialchars($book_description); ?></p>
                    <a href="#" class="read-more-btn" id="readMoreBtn">Read more</a>
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
                        <input type="number" class="page-input" id="currentPageInput" value="<?php echo htmlspecialchars($pages_read); ?>" min="0" max="<?php echo htmlspecialchars($book_page_count); ?>">
                        <span class="page-separator">of</span>
                        <span class="total-pages" id="totalPages"><?php echo htmlspecialchars($book_page_count); ?></span>
                    </div>
                    <button class="save-progress-btn" id="saveProgressBtn" data-book-id="<?php echo htmlspecialchars($book_id); ?>">Update Progress</button>
                </div>
            </div>

            <!-- Similar Books -->
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
            <div class="reviews-list" id="reviewsList"></div>
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
                    <button type="submit" class="submit-review-btn" id="submitForm">Submit Review</button>
                </form>
            </div>
        </div>
        
        <!-- Library Popup -->
        <div class="library-popup-overlay" id="libraryPopupOverlay" style="display: none;">
            <div class="library-popup">
                <button class="close-library-popup" id="closeLibraryPopup">&times;</button>
                <h3>Biblioteci unde găsești această carte</h3>
                <ul class="library-list" id="libraryList">
                    <!-- aici vin biblioteci din js -->
                </ul>
            </div>
        </div>
    </div> 
</section>