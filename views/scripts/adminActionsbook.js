// Admin functionality for book management
const deleteBookBtn = document.getElementById('deleteBookBtn');
const editBookBtn = document.getElementById('editBookBtn');

if (deleteBookBtn) {
    deleteBookBtn.addEventListener('click', () => {
        const bookId = deleteBookBtn.getAttribute('data-book-id');
        
        if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
            fetch('/ShelfControl/admin/delete-book', {
                method: 'DELETE',
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
        console.log('Edit button clicked for book ID:', bookId);
        
        if (!bookId) {
            alert('No book ID found. Please refresh the page and try again.');
            return;
        }
        
        // Show loading state
        editBookBtn.disabled = true;
        
        let responseText; // Define a variable to store the response text
        
        // Fetch book details with better error handling
        fetch(`/ShelfControl/admin/get-book-details?id=${encodeURIComponent(bookId)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', Object.fromEntries(response.headers.entries()));
            console.log('Response ok:', response.ok);
            console.log('Response url:', response.url);
            
            // Check if response is actually JSON
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status} - ${response.statusText}`);
            }
            
            return response.text();
        })
        .then(text => {
            console.log('Raw response length:', text.length);
            console.log('Raw response (first 500 chars):', text.substring(0, 500));
            
            responseText = text;
            
            if (!text || text.trim() === '') {
                throw new Error('Empty response from server');
            }
            
            // Check for HTML error pages or non-JSON responses
            if (text.trim().startsWith('<') || text.trim().startsWith('<!DOCTYPE')) {
                console.error('Received HTML instead of JSON:', text);
                throw new Error('Server returned HTML instead of JSON - check server logs');
            }
            
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("JSON parse error:", e.message);
                console.error("Response was:", text);
                throw new Error(`Invalid JSON response: ${e.message}`);
            }
        })
        .then(data => {
            console.log('Parsed response data:', data);
            
            if (data.success) {
                console.log('Book data received:', data.book);
                const adminModal = document.getElementById('add-book-modal');
                if (adminModal) {
                    fillEditForm(data.book);
                    openModalForEdit();
                } else {
                    throw new Error("Modal not found on page");
                }
            } else {
                throw new Error(data.message || "Unknown error in response");
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            console.error("Error stack:", error.stack);
            if (responseText) {
                console.error("Server response was:", responseText);
            }
            
            let errorMessage = `Error getting book details: ${error.message}`;
            
            // Provide more specific error messages
            if (error.message.includes('Failed to fetch')) {
                errorMessage += '\n\nThis could be due to:\n- Network connectivity issues\n- Server not responding\n- CORS issues';
            } else if (error.message.includes('Empty response')) {
                errorMessage += '\n\nThe server returned an empty response. Check server logs for PHP errors.';
            }
            
            alert(errorMessage);
        })
        .finally(() => {
            // Reset button state
            editBookBtn.disabled = false;
        });
    });
}

function fillEditForm(book) {
    console.log('Book data received:', book);
    
    try {
        // Find form inputs and populate them - gracefully handle different field name cases
        const setFieldValue = (fieldId, value) => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = value || '';
                console.log(`Set ${fieldId} to:`, value || '');
            } else {
                console.warn(`Field ${fieldId} not found`);
            }
        };
        
        setFieldValue('title', book.TITLE);
        setFieldValue('author', book.AUTHOR_NAME);
        setFieldValue('translator', book.TRANSLATOR_NAME);
        setFieldValue('language', book.LANGUAGE);
        setFieldValue('publication', book.PUBLICATION_YEAR);
        setFieldValue('pages', book.PAGES);
        setFieldValue('isbn', book.ISBN);
        setFieldValue('publisher', book.PUBLISHING_HOUSE_NAME);
        setFieldValue('subpublisher', book.SUB_PUBLISHER_NAME);
        setFieldValue('genre', book.GENRE);
        setFieldValue('summary', book.SUMMARY);
        
        // Handle cover URL
        const coverUrl = book.COVER_URL || '';
        setFieldValue('cover-url', coverUrl);
        
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
        let hiddenField = document.getElementById('edit_mode');
        if (!hiddenField) {
            hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'edit_mode';
            hiddenField.value = 'true';
            hiddenField.id = 'edit_mode';
            const form = document.getElementById('book-form');
            if (form) {
                form.appendChild(hiddenField);
            }
        }
        
        // Add book ID field
        let bookIdField = document.getElementById('book_id');
        if (!bookIdField) {
            bookIdField = document.createElement('input');
            bookIdField.type = 'hidden';
            bookIdField.name = 'book_id';
            bookIdField.value = book.BOOK_ID;
            bookIdField.id = 'book_id';
            const form = document.getElementById('book-form');
            if (form) {
                form.appendChild(bookIdField);
            }
        } else {
            bookIdField.value = book.BOOK_ID;
        }
        
        // Change submit button text
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.textContent = 'Update Book';
        }
        
        console.log('Form filled successfully');
        
    } catch (error) {
        console.error('Error filling form:', error);
        alert('Error filling form with book data');
    }
}

function openModalForEdit() {
    try {
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
            
            console.log('Modal opened for editing');
        } else {
            console.error('Modal not found');
            alert('Edit modal not found on this page');
        }
    } catch (error) {
        console.error('Error opening modal:', error);
        alert('Error opening edit modal');
    }
}

// Add form submission handler for edit mode
document.addEventListener('DOMContentLoaded', function() {
    const bookForm = document.getElementById('book-form');
    if (bookForm) {
        bookForm.addEventListener('submit', function(e) {
            const editMode = document.getElementById('edit_mode');
            if (editMode && editMode.value === 'true') {
                e.preventDefault();
                
                // Handle update submission
                const formData = new FormData(bookForm);
                
                fetch('/ShelfControl/admin/update-book', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Book updated successfully!');
                        window.location.reload();
                    } else {
                        alert(`Error updating book: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the book.');
                });
            }
        });
    }
});