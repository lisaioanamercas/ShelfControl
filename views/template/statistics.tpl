<section class="statistics section">
    <div class="container">
        <h1 class="section-title">Reading Statistics & Analytics</h1>
        
        <!-- Overview Cards -->
        <div class="stats-overview">
            <div class="overview-cards">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="ri-book-line"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="overview-card__number"><?php echo count($bookStats); ?></h3>
                        <p class="overview-card__label">Popular Books</p>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="ri-user-line"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="overview-card__number"><?php echo count($userStats); ?></h3>
                        <p class="overview-card__label">Active Readers</p>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="ri-star-line"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="overview-card__number">
                            <?php 
                                $totalRating = 0;
                                $ratedBooks = 0;
                                foreach ($bookStats as $book) {
                                    if ($book['AVERAGE_RATING'] > 0) {
                                        $totalRating += $book['AVERAGE_RATING'];
                                        $ratedBooks++;
                                    }
                                }
                                echo $ratedBooks > 0 ? number_format($totalRating / $ratedBooks, 1) : '0.0';
                            ?>
                        </h3>
                        <p class="overview-card__label">Avg Rating</p>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="ri-eye-line"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="overview-card__number">
                            <?php 
                                $totalReaders = 0;
                                foreach ($bookStats as $book) {
                                    $totalReaders += $book['TOTAL_READERS'];
                                }
                                echo $totalReaders;
                            ?>
                        </h3>
                        <p class="overview-card__label">Total Readers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="stats-tabs">
            <button class="stats-tab active" data-tab="books">
                <i class="ri-book-line"></i>
                Popular Books
            </button>
            <button class="stats-tab" data-tab="users">
                <i class="ri-user-line"></i>
                Top Readers
            </button>
            <button class="stats-tab" data-tab="genres">
                <i class="ri-price-tag-3-line"></i>
                Genres
            </button>
        </div>

                <!-- Tab Content: Popular Books -->
        <div class="stats-tab-content active" id="books-tab">
            <div class="stats-section">
                <h2 class="stats-section__title">Most Popular Books</h2>
                
                <!-- Use existing book-library__container structure -->
                <div class="book-library__container container grid">
                    <?php if (!empty($bookStats)): ?>
                        <?php foreach (array_slice($bookStats, 0, 20) as $index => $book): ?>
                            <div class="book-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                                <div class="book-card__rank">
                                    #<?php echo $index + 1; ?>
                                </div>
                                
                                <div class="book-card__cover">
                                    <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                        <img src="<?php echo htmlspecialchars($book['COVER_URL'] ?: 'assets/img/default-book.png'); ?>" 
                                             alt="<?php echo htmlspecialchars($book['TITLE']); ?>" 
                                             class="book-card__img">
                                    </a>
                                </div>
                                
                                <div class="book-card__data">
                                    <h3 class="book-card__title">
                                        <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>">
                                            <?php echo htmlspecialchars($book['TITLE']); ?>
                                        </a>
                                    </h3>
                                    <p class="book-card__author"><?php echo htmlspecialchars($book['AUTHOR_NAME']); ?></p>
                                    
                                    <div class="book-card__rating">
                                        <?php 
                                            $rating = $book['AVERAGE_RATING'] ?: 0;
                                            $fullStars = floor($rating);
                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                            
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $fullStars) {
                                                    echo '<i class="ri-star-fill"></i>';
                                                } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                                    echo '<i class="ri-star-half-fill"></i>';
                                                } else {
                                                    echo '<i class="ri-star-line"></i>';
                                                }
                                            }
                                        ?>
                                        <span><?php echo number_format($rating, 1); ?></span>
                                    </div>
                                    
                                    <div class="book-card__stats">
                                        <div class="book-stat">
                                            <i class="ri-user-line"></i>
                                            <span><?php echo $book['TOTAL_READERS']; ?> readers</span>
                                        </div>
                                        <div class="book-stat">
                                            <i class="ri-chat-3-line"></i>
                                            <span><?php echo $book['REVIEW_COUNT']; ?> reviews</span>
                                        </div>
                                    </div>
                                    
                                    <div class="book-card__progress">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?php echo min(100, ($book['TOTAL_READERS'] / max(1, $bookStats[0]['TOTAL_READERS'])) * 100); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="book-card__actions">
                                    <a href="/ShelfControl/book-details?id=<?php echo $book['BOOK_ID']; ?>" class="book-card__btn" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <button class="book-card__btn" title="Add to Library">
                                        <i class="ri-bookmark-line"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-message">No book statistics available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
         <div class="stats-tab-content" id="users-tab">
              <div class="stats-section">
                <h2 class="stats-section__title">Top Readers Leaderboard</h2>
                        <div class="leaderboard">
                            <?php if (!empty($userStats)): ?>
                                    <?php foreach (array_slice($userStats, 0, 10) as $index => $user): ?>
                                            <div class="leaderboard-item" style="animation-delay: <?php echo $index * 0.1; ?>s">
                                                <div class="leaderboard-rank">
                                                       <?php if ($index === 0): ?>
                                                        <i class="ri-trophy-line trophy-gold"></i>
                                                    <?php elseif ($index === 1): ?>
                                                        <i class="ri-trophy-line trophy-silver"></i>
                                                    <?php elseif ($index === 2): ?>
                                                        <i class="ri-trophy-line trophy-bronze"></i>
                                                    <?php endif; ?>
                                                 </div>
                                                 <div class="leaderboard-avatar">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                                <div class="leaderboard-info">
                                                    <h4 class="leaderboard-name">
                                                        <?php 
                                                            // Debug and handle the username
                                                            if (is_array($user) && isset($user['USERNAME'])) {
                                                                echo htmlspecialchars($user['USERNAME']);
                                                            } elseif (is_array($user) && isset($user['username'])) {
                                                                echo htmlspecialchars($user['username']);
                                                            } else {
                                                                echo 'Unknown User';
                                                            }
                                                        ?>
                                                    </h4>
                                                        <span class="stat-item">
                                                        <i class="ri-book-read-line"></i>
                                                        <?php 
                                                            if (is_array($user)) {
                                                                $booksRead = isset($user['BOOKS_READ']) ? (int)$user['BOOKS_READ'] : 
                                                                            (isset($user['books_read']) ? (int)$user['books_read'] : 0);
                                                                echo $booksRead;
                                                            } else {
                                                                echo '0';
                                                            }
                                                        ?> read
                                                    </span>
                                                     <span class="stat-item">
                                                    <i class="ri-book-open-line"></i>
                                                    <?php 
                                                        if (is_array($user)) {
                                                            $currentlyReading = isset($user['CURRENTLY_READING']) ? (int)$user['CURRENTLY_READING'] : 
                                                                            (isset($user['currently_reading']) ? (int)$user['currently_reading'] : 0);
                                                            echo $currentlyReading;
                                                        } else {
                                                            echo '0';
                                                        }
                                                    ?> reading
                                                </span>
                                                        <span class="stat-item">
                                                    <i class="ri-star-line"></i>
                                                    <?php 
                                                        if (is_array($user)) {
                                                            $rating = isset($user['AVERAGE_RATING']) ? (float)$user['AVERAGE_RATING'] : 
                                                                    (isset($user['average_rating']) ? (float)$user['average_rating'] : 0);
                                                            echo number_format($rating, 1);
                                                        } else {
                                                            echo '0.0';
                                                        }
                                                    ?> avg
                                                </span>
                                                   </div>
                                                </div>
                                                       <div class="leaderboard-score">
                                    <span class="score-number">
                                        <?php 
                                            if (is_array($user)) {
                                                // Debug the actual values
                                                $booksReadRaw = $user['BOOKS_READ'] ?? $user['BOOKS_READ'] ?? 0;
                                                $currentlyReadingRaw = $user['CURRENTLY_READING'] ?? $user['CURRENTLY_READING'] ?? 0;
                                                
                                                error_log("DEBUG - Books Read Raw: " . var_export($booksReadRaw, true) . " (type: " . gettype($booksReadRaw) . ")");
                                                error_log("DEBUG - Currently Reading Raw: " . var_export($currentlyReadingRaw, true) . " (type: " . gettype($currentlyReadingRaw) . ")");
                                                
                                                // Safe conversion function
                                                function safeNumericConvert($value) {
                                                    if (is_numeric($value)) {
                                                        return (int)$value;
                                                    }
                                                    if (is_string($value)) {
                                                        // Handle European format
                                                        $cleaned = str_replace(',', '.', $value);
                                                        if (is_numeric($cleaned)) {
                                                            return (int)floatval($cleaned);
                                                        }
                                                        // Extract only digits if string contains other characters
                                                        if (preg_match('/\d+/', $value, $matches)) {
                                                            return (int)$matches[0];
                                                        }
                                                    }
                                                    return 0;
                                                }
                                                
                                                $booksRead = safeNumericConvert($booksReadRaw);
                                                $currentlyReading = safeNumericConvert($currentlyReadingRaw);
                                                
                                                error_log("DEBUG - Final values: booksRead=$booksRead, currentlyReading=$currentlyReading");
                                                
                                                $booksRead = safeNumericConvert($booksReadRaw);
                                                    $currentlyReading = safeNumericConvert($currentlyReadingRaw);
                                                   echo $booksRead + $currentlyReading;
                                            } else {
                                                echo '0';
                                            }
                                        ?>
                                    </span>
                                    <span class="score-label">books</span>
                                </div>
                            </div>
                                    <?php endforeach; ?>
                              <?php else: ?>
                                <p class="empty-message">No user statistics available.</p>
                            <?php endif; ?>
                      </div>
               </div>
         </div>

        <!-- Tab Content: Genre Analytics -->
        <div class="stats-tab-content" id="genres-tab">
            <div class="stats-section">
                <h2 class="stats-section__title">Genre Popularity</h2>
                <div class="genre-stats">
                    <!-- This would require additional data from your view -->
                    <p class="coming-soon">Genre analytics coming soon...</p>
                </div>
            </div>
        </div>
    </div>
</section>