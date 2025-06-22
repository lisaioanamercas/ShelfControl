<section class="news-feed section" id="review">
    <div class="container">
        <!-- Header -->
        <div class="news-feed__header">
            <h2 class="news-feed__title">News Feed</h2>
            <button class="button add-news-btn" id="add-news-btn">
                <i class="ri-add-line"></i>
                <span>Add News</span>
            </button>
        </div>

        <!-- Loader -->
        <div class="loader" id="loader"></div>

        <!-- News List -->
        <div class="news-list" id="news-list">
            <!-- News cards will be dynamically added here -->
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="empty-state" style="display: none;">
            <i class="ri-newspaper-line"></i>
            <h3>No news available</h3>
            <p>Be the first to add some news to the feed!</p>
        </div>
    </div>
</section>

<!-- Add News Modal -->
<div class="modal-overlay" id="modal-overlay">
    <div class="modal">        <div class="modal__header">
            <h3 class="modal__title">Add News</h3>
            <button type="button" class="modal__close" id="news-modal-close">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <form id="news-form">
            <div class="form-group">
                <label class="form-label" for="news-type">Type</label>
                <select class="form-select" id="news-type" required>
                    <option value="">Select news type</option>
                    <option value="review">Review</option>
                    <option value="launch">Book Launch</option>
                    <option value="announcement">Announcement</option>
                    <option value="ranking">Ranking Update</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="news-title">Title</label>
                <input type="text" class="form-input" id="news-title" required placeholder="Enter news title">
            </div>

            <div class="form-group">
                <label class="form-label" for="news-description">Description</label>
                <textarea class="form-textarea" id="news-description" required placeholder="Enter news description"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="news-link">Link (optional)</label>
                <input type="url" class="form-input" id="news-link" placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="news-author">Author (optional)</label>
                <input type="text" class="form-input" id="news-author" placeholder="Enter author name">
            </div>

            <button type="submit" class="button" style="width: 100%;">
                <span>Submit News</span>
            </button>
        </form>
    </div>
</div>

