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
                        <div class="status-option group-reading" data-status="group-reading">group reading</div>
 
                    </div>
                </div>
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
                    <div class="book-title-header">
                <h1 class="book-title">
                    <a href="/ShelfControl/books/edition/<?php echo urlencode($book_title); ?>" class="metadata-link">{$book_title}</a>
                </h1>
                
                <!-- BUTOANE PENTRU ADMIN - Top right position -->
                <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                    <div class="admin-actions">
                        <button class="admin-action__btn edit-book-btn" id="editBookBtn" data-book-id="<?php echo $book_id; ?>" title="Edit Book">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="admin-action__btn delete-book-btn" id="deleteBookBtn" data-book-id="<?php echo $book_id; ?>" title="Delete Book">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
                        
                    <h2 class="book-author" data-author-name="{$book_author}">
                        <a href="/ShelfControl/books/author/<?php echo urlencode($book_author); ?>" class="metadata-link">{$book_author}</a>
                    </h2> 

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
                    <span class="book-meta__label">Genre:</span>
                    <span class="book-meta__value">
                        <a href="/ShelfControl/books/genre/<?php echo urlencode($book_genre); ?>" class="metadata-link">{$book_genre}</a>
                    </span>
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
                    <div class="book-summary__content" id="bookSummary">
                        <p><?php echo substr($book_description, 0, 300); ?>...</p>
                        <!-- Store the full description in a hidden element -->
                        <p class="full-description" style="display: none;"><?php echo $book_description; ?></p>
                        <a href="#" class="read-more-btn" id="readMoreBtn">Read more</a>
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
                <div class="reviews-list">
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-header">
                        <span class="reviewer-name"><?php echo htmlspecialchars($review['USERNAME'] ?? 'Anonim'); ?></span>
                        <span class="review-rating">
                            <?php
                                $stars = intval($review['STARS']);
                                for ($i = 0; $i < $stars; $i++) echo '★';
                                for ($i = 5; $i < $stars; $i++) echo '☆';
                            ?>
                        </span>
                    </div>
                    <div class="review-content">
                        <?php echo nl2br(htmlspecialchars($review['TEXT'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nu există recenzii pentru această carte.</p>
        <?php endif; ?>
      </div>
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
                    <button type="submit" class="submit-review-btn" id="submitForm">Submit Review</button>
                </form>
            </div>
        </div>
        <!-- Library Popup -->
        <div class="library-popup-overlay" id="libraryPopupOverlay" style="display: none;">
            <div class="library-popup">
                <button class="close-library-popup" id="closeLibraryPopup">&times;</button>
                <h3>Biblioteci unde găsești această carte</h3>
                <ul class="library-list">
                    <li>Biblioteca Națională a României</li>
                    <li>Biblioteca Centrală Universitară București</li>
                    <li>Biblioteca Metropolitană București</li>
                    <li>Biblioteca Județeană Cluj</li>
                    <li>Biblioteca Județeană Timiș</li>
                </ul>
            </div>
        </div>`
    </div> 
</div>
