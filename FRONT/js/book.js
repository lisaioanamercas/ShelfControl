// ===================================== BOOK DETAILS =========================================
document.addEventListener('DOMContentLoaded', () => {
    // Status dropdown functionality
    const statusBtn = document.getElementById('statusBtn');
    const statusOptions = document.getElementById('statusOptions');
    const currentStatus = document.getElementById('currentStatus');
    const readingProgress = document.getElementById('readingProgress');
    
    // Toggle status dropdown
    if (statusBtn) {
        statusBtn.addEventListener('click', () => {
            statusOptions.classList.toggle('active');
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!statusBtn.contains(e.target) && !statusOptions.contains(e.target)) {
            statusOptions.classList.remove('active');
        }
    });
    
    // Select status option
    const statusOptionElements = document.querySelectorAll('.status-option');
    statusOptionElements.forEach(option => {
        option.addEventListener('click', () => {
            const selectedStatus = option.getAttribute('data-status');
            currentStatus.textContent = selectedStatus;
            statusOptions.classList.remove('active');
            
            // Show reading progress if status is "reading"
            if (selectedStatus === 'reading') {
                readingProgress.classList.add('active');
            } else {
                readingProgress.classList.remove('active');
            }
            
            // Save to localStorage
            localStorage.setItem('bookStatus', selectedStatus);
        });
    });
    
    // Initialize status from localStorage
    const savedStatus = localStorage.getItem('bookStatus');
    if (savedStatus) {
        currentStatus.textContent = savedStatus;
        if (savedStatus === 'reading') {
            readingProgress.classList.add('active');
        }
    }
    
    // Read More functionality
    const readMoreBtn = document.getElementById('readMoreBtn');
    const bookSummary = document.getElementById('bookSummary');
    
    if (readMoreBtn) {
        readMoreBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Full description (would typically come from an API or database)
            const fullDescription = `«Quand la sonnerie a encore retenti, que la porte du box s'est ouverte, c'est le silence de la salle qui est monté vers moi, le silence, et cette singulière sensation que j'ai eue lorsque j'ai constaté que le jeune journaliste avait détourné les yeux. Je n'ai pas regardé du côté de Marie. Je n'en ai pas eu le temps parce que le président m'a dit dans une forme bizarre que j'aurais la tête tranchée sur une place publique au nom du peuple français...»
            
            Ce roman retrace l'histoire d'un homme ordinaire qui se retrouve condamné parce qu'il ne joue pas le jeu. Étranger à la société dans laquelle il vit, indifférent aux émotions humaines, Meursault assiste à son procès comme s'il s'agissait d'un autre. Albert Camus explore dans ce roman existentialiste les thèmes de l'absurdité de la vie et de l'authenticité face aux conventions sociales.`;
            
            // Update content and change button text
            bookSummary.innerHTML = `<p>${fullDescription}</p>`;
            readMoreBtn.style.display = 'none';
        });
    }
    
    // Reading Progress functionality
    const currentPageInput = document.getElementById('currentPageInput');
    const totalPages = document.getElementById('totalPages');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    
    if (saveProgressBtn) {
        saveProgressBtn.addEventListener('click', () => {
            // Get values
            const currentPage = parseInt(currentPageInput.value);
            const total = parseInt(totalPages.textContent);
            
            // Validate
            if (isNaN(currentPage) || currentPage < 0 || currentPage > total) {
                alert('Please enter a valid page number');
                return;
            }
            
            // Calculate percentage
            const percentage = Math.round((currentPage / total) * 100);
            
            // Update UI
            progressFill.style.width = `${percentage}%`;
            progressText.textContent = `${percentage}%`;
            
            // Save to localStorage
            localStorage.setItem('currentPage', currentPage);
            localStorage.setItem('readingPercentage', percentage);
        });
    }
    
    // Initialize reading progress from localStorage
    const savedPage = localStorage.getItem('currentPage');
    const savedPercentage = localStorage.getItem('readingPercentage');
    
    if (savedPage) {
        currentPageInput.value = savedPage;
    }
    
    if (savedPercentage) {
        progressFill.style.width = `${savedPercentage}%`;
        progressText.textContent = `${savedPercentage}%`;
    }
    
    // Book actions (owned/buy buttons)
    const ownedBtn = document.getElementById('ownedBtn');
    const buyBtn = document.getElementById('buyBtn');
    
    if (ownedBtn) {
        ownedBtn.addEventListener('click', () => {
            ownedBtn.classList.toggle('active');
            localStorage.setItem('bookOwned', ownedBtn.classList.contains('active'));
        });
    }
    
    if (buyBtn) {
        buyBtn.addEventListener('click', () => {
            // Open a bookseller website (example)
            window.open('https://www.bookstore.com/9780679720201', '_blank');
        });
    }
    
    // Initialize book owned status from localStorage
    const bookOwned = localStorage.getItem('bookOwned') === 'true';
    if (bookOwned) {
        ownedBtn.classList.add('active');
    }
    
    // Add review functionality
    createReviewSection();
});

// Create and inject review section
function createReviewSection() {
    const bookDetailsContainer = document.querySelector('.book-details__container');
    
    // Create review section
    const reviewSection = document.createElement('div');
    reviewSection.className = 'book-review-section';
    reviewSection.innerHTML = `
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
    `;
    
    // Append to container
    bookDetailsContainer.appendChild(reviewSection);
    
    // Add CSS for review section
    addReviewStyles();
    
    // Set up event listeners for review functionality
    setupReviewFunctionality();
    
    // Load existing reviews
    loadReviews();
}

// Add necessary CSS for reviews
function addReviewStyles() {
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        /* Review Section Styles */
        .book-review-section {
            grid-column: 1 / -1;
            border-top: 1px solid var(--border-color);
            padding-top: 2rem;
            margin-top: 2rem;
        }
        
        .book-reviews__title {
            font-size: var(--h3-font-size);
            margin-bottom: 1rem;
        }
        
        .write-review-btn {
            padding: 0.75rem 1.5rem;
            background-color: var(--first-color);
            color: var(--white-color);
            border: none;
            border-radius: 4px;
            font-weight: var(--font-medium);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            transition: background-color 0.3s;
        }
        
        .write-review-btn:hover {
            background-color: hsl(0, 85%, 40%);
        }
        
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .review-item {
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--container-color);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--first-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white-color);
            font-weight: var(--font-bold);
        }
        
        .reviewer-name {
            font-weight: var(--font-medium);
        }
        
        .review-date {
            color: var(--text-color-light);
            font-size: var(--smaller-font-size);
        }
        
        .review-rating {
            display: flex;
            gap: 0.25rem;
            color: #FFD700;
        }
        
        .review-content {
            color: var(--text-color);
            line-height: 1.6;
        }
        
        /* Review Form Overlay */
        .review-form-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .review-form-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .review-form-container {
            width: 90%;
            max-width: 600px;
            background-color: var(--container-color);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
        
        .review-form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .close-review-form {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--title-color);
        }
        
        .star-rating {
            margin-bottom: 1.5rem;
        }
        
        .stars {
            display: flex;
            gap: 0.5rem;
            font-size: 1.5rem;
            color: #CCCCCC;
            margin-top: 0.5rem;
        }
        
        .stars .star {
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .stars .star.active {
            color: #FFD700;
        }
        
        .review-text {
            margin-bottom: 1.5rem;
        }
        
        .review-text label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: var(--font-medium);
        }
        
        .review-text textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            resize: vertical;
            font-family: inherit;
        }
        
        .submit-review-btn {
            padding: 0.75rem 1.5rem;
            background-color: var(--first-color);
            color: var(--white-color);
            border: none;
            border-radius: 4px;
            font-weight: var(--font-medium);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-review-btn:hover {
            background-color: hsl(0, 85%, 40%);
        }
        
        /* Dark Theme Styles */
        .dark-theme .review-item {
            background-color: var(--dark-card-color);
            border-color: var(--dark-border-color);
        }
        
        .dark-theme .review-form-container {
            background-color: var(--dark-card-color);
        }
        
        .dark-theme .review-text textarea {
            background-color: var(--dark-background);
            border-color: var(--dark-border-color);
            color: var(--dark-text-color);
        }
        
        .dark-theme .close-review-form {
            color: var(--dark-text-color);
        }
        
        @media screen and (min-width: 768px) {
            .book-review-section {
                margin-top: 3rem;
            }
        }
    `;
    
    document.head.appendChild(styleSheet);
}

// Set up review functionality
function setupReviewFunctionality() {
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const closeReviewForm = document.getElementById('closeReviewForm');
    const reviewFormOverlay = document.getElementById('reviewFormOverlay');
    const reviewForm = document.getElementById('reviewForm');
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');
    
    // Open review form
    writeReviewBtn.addEventListener('click', () => {
        reviewFormOverlay.classList.add('active');
    });
    
    // Close review form
    closeReviewForm.addEventListener('click', () => {
        reviewFormOverlay.classList.remove('active');
    });
    
    // Click outside to close
    reviewFormOverlay.addEventListener('click', (e) => {
        if (e.target === reviewFormOverlay) {
            reviewFormOverlay.classList.remove('active');
        }
    });
    
    // Star rating functionality
    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const value = parseInt(star.getAttribute('data-value'));
            highlightStars(value);
        });
        
        star.addEventListener('mouseout', () => {
            const currentRating = parseInt(ratingValue.value);
            highlightStars(currentRating);
        });
        
        star.addEventListener('click', () => {
            const value = parseInt(star.getAttribute('data-value'));
            ratingValue.value = value;
            highlightStars(value);
        });
    });
    
    // Submit review form
    reviewForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const rating = parseInt(ratingValue.value);
        const reviewContent = document.getElementById('reviewContent').value.trim();
        
        // Validate
        if (rating === 0) {
            alert('Please select a rating');
            return;
        }
        
        if (reviewContent === '') {
            alert('Please write a review');
            return;
        }
        
        // Create review object
        const review = {
            id: Date.now(),
            user: 'You',
            rating: rating,
            content: reviewContent,
            date: new Date().toLocaleDateString()
        };
        
        // Save review
        saveReview(review);
        
        // Reset form and close overlay
        reviewForm.reset();
        ratingValue.value = 0;
        highlightStars(0);
        reviewFormOverlay.classList.remove('active');
        
        // Refresh reviews list
        loadReviews();
    });
}

// Highlight stars based on rating
function highlightStars(rating) {
    const stars = document.querySelectorAll('.star');
    
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('ri-star-line');
            star.classList.add('ri-star-fill', 'active');
        } else {
            star.classList.remove('ri-star-fill', 'active');
            star.classList.add('ri-star-line');
        }
    });
}

// Save review to localStorage
function saveReview(review) {
    // Get existing reviews or initialize empty array
    const reviews = JSON.parse(localStorage.getItem('bookReviews')) || [];
    
    // Add new review
    reviews.push(review);
    
    // Save back to localStorage
    localStorage.setItem('bookReviews', JSON.stringify(reviews));
}

// Load reviews from localStorage
function loadReviews() {
    const reviewsList = document.getElementById('reviewsList');
    const reviews = JSON.parse(localStorage.getItem('bookReviews')) || [];
    
    // Clear existing reviews
    reviewsList.innerHTML = '';
    
    if (reviews.length === 0) {
        reviewsList.innerHTML = '<p>No reviews yet. Be the first to review this book!</p>';
        return;
    }
    
    // Sort reviews by date (newest first)
    reviews.sort((a, b) => b.id - a.id);
    
    // Add reviews to the list
    reviews.forEach(review => {
        const reviewElement = document.createElement('div');
        reviewElement.className = 'review-item';
        
        const starIcons = Array(5).fill('').map((_, index) => {
            return index < review.rating 
                ? '<i class="ri-star-fill"></i>' 
                : '<i class="ri-star-line"></i>';
        }).join('');
        
        reviewElement.innerHTML = `
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">${review.user.charAt(0)}</div>
                    <div>
                        <div class="reviewer-name">${review.user}</div>
                        <div class="review-date">${review.date}</div>
                    </div>
                </div>
                <div class="review-rating">
                    ${starIcons}
                </div>
            </div>
            <div class="review-content">
                ${review.content}
            </div>
        `;
        
        reviewsList.appendChild(reviewElement);
    });
}