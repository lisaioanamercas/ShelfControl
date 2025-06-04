// Admin functionality for book management
const deleteBookBtn = document.getElementById('deleteBookBtn');
const editBookBtn = document.getElementById('editBookBtn');

if (deleteBookBtn) {
    deleteBookBtn.addEventListener('click', () => {
        const bookId = deleteBookBtn.getAttribute('data-book-id');
        
        if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
            fetch('/ShelfControl/admin/delete-book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `book_id=${bookId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Book deleted successfully.');
                    window.location.href = '/ShelfControl/library-all';
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the book.');
            });
        }
    });
}

if (editBookBtn) {
    editBookBtn.addEventListener('click', () => {
        const bookId = editBookBtn.getAttribute('data-book-id');
        
        // Fetch book details
        fetch(`/ShelfControl/admin/get-book-details?id=${bookId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Open the add book modal with pre-filled data
                    const adminModal = document.getElementById('add-book-modal');
                    if (adminModal) {
                        fillEditForm(data.book);
                        openModalForEdit();
                    } else {
                        // If modal not on page, redirect to page with modal and parameters
                        window.location.href = `/ShelfControl/home?openAdminModal=true&editBook=${bookId}`;
                    }
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while getting book details.');
            });
    });
}

function fillEditForm(book) {
    // Find form inputs and populate them
    document.getElementById('title').value = book.TITLE || '';
    document.getElementById('author').value = book.AUTHOR_NAME || '';
    document.getElementById('translator').value = book.TRANSLATOR_NAME || '';
    document.getElementById('language').value = book.LANGUAGE || '';
    document.getElementById('publication').value = book.PUBLICATION_YEAR || '';
    document.getElementById('pages').value = book.PAGES || '';
    document.getElementById('isbn').value = book.ISBN || '';
    document.getElementById('publisher').value = book.PUBLISHING_HOUSE_NAME || '';
    document.getElementById('subpublisher').value = book.SUB_PUBLISHER_NAME || '';
    document.getElementById('genre').value = book.GENRE || '';
    document.getElementById('summary').value = book.SUMMARY || '';
    
    // Handle cover URL
    const coverUrl = book.COVER_URL || '';
    document.getElementById('cover-url').value = coverUrl;
    if (coverUrl) {
        // Display the cover preview
        const coverPreview = document.getElementById('cover-preview');
        const coverImage = document.getElementById('cover-image');
        if (coverPreview && coverImage) {
            coverImage.src = coverUrl;
            coverPreview.style.display = 'block';
        }
    }
    
    // Add hidden field for edit mode
    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'edit_mode';
    hiddenField.value = 'true';
    hiddenField.id = 'edit_mode';
    document.getElementById('book-form').appendChild(hiddenField);
    
    // Add book ID field
    const bookIdField = document.createElement('input');
    bookIdField.type = 'hidden';
    bookIdField.name = 'book_id';
    bookIdField.value = book.BOOK_ID;
    bookIdField.id = 'book_id';
    document.getElementById('book-form').appendChild(bookIdField);
    
    // Change submit button text
    document.getElementById('submit-btn').textContent = 'Update Book';
}

function openModalForEdit() {
    // Get the modal and show it
    const adminModal = document.getElementById('add-book-modal');
    if (adminModal) {
        if (document.body.classList.contains('dark-theme')) {
            adminModal.classList.add('dark-theme');
        }
        adminModal.classList.add('show-modal');
        document.body.style.overflow = 'hidden';
        
        // Update modal title
        const modalTitle = adminModal.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.textContent = 'Edit Book';
        }
    }
}