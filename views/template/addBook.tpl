<!-- Add Book Modal -->
<div class="add-book-modal" id="add-book-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Book</h2>
            <i class="ri-close-line modal-close" id="modal-close"></i>
        </div>
        
        <form class="book-form" id="book-form">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="title">Title *</label>
                    <input type="text" id="title" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="author">Author *</label>
                    <input type="text" id="author" name="author" class="form-input" required>
                </div>
            </div>

            <!-- ============FIELD NOU PT TRADUCATOR +++++++++++++++++ -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="translator">Translator</label>
                    <input type="text" id="translator" name="translator" class="form-input" placeholder="Optional">
                </div>
                <div class="form-group">
                    <label class="form-label" for="language">Language</label>
                    <input type="text" id="language" name="language" class="form-input">
                </div>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="publication">Publication Year</label>
                    <input type="number" id="publication" name="publication" class="form-input" min="1" max="2025">
                </div>
                <div class="form-group">
                    <label class="form-label" for="pages">Pages</label>
                    <input type="number" id="pages" name="pages" class="form-input" min="1">
                </div>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-input">
                </div>
                    <div class="form-group">
                    <label class="form-label" for="genre">Genre</label>
                    <input type="text" id="genre" name="genre" class="form-input" placeholder="Fiction, Science Fiction, etc.">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="publisher">Publisher</label>
                    <input type="text" id="publisher" name="publisher" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label" for="subpublisher">Sub-publisher</label>
                    <input type="text" id="subpublisher" name="subpublisher" class="form-input" placeholder="Optional">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="summary">Book Description</label>
                    <textarea id="summary" name="summary" class="form-textarea description-textarea" rows="6" placeholder="Enter a description of the book..."></textarea>
                </div>
            </div>


            <!-- Book Cover Upload -->
            <div class="cover-upload-section">
                <!-- Book Cover URL Input -->
                <div class="form-group">
                    <label class="form-label" for="cover-url">Book Cover URL</label>
                    <input type="url" id="cover-url" name="cover_url" class="form-input" placeholder="https://example.com/book-cover.jpg">
                    <p class="upload-subtext">Paste a direct link to the book cover image</p>
                </div>

                <!-- Cover Preview -->
                <div class="cover-preview" id="cover-preview" style="display: none;">
                    <img id="cover-image" src="" alt="Book cover preview">
                    <button type="button" class="remove-cover-btn" id="remove-cover-btn">
                        <i class="ri-delete-bin-line"></i> Remove
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancel-btn">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submit-btn">Add Book</button>
            </div>
        </form>
    </div>
</div>

<!-- Success Message -->
<div class="success-message" id="success-message">
    <i class="ri-check-line"></i>
    <span>Book added successfully!</span>
</div>