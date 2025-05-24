
// ===================================== Progress handling for read books =========================================
document.addEventListener('DOMContentLoaded', function() {
    // Set up click events for edit buttons
    const editButtons = document.querySelectorAll('.edit-progress-btn');
    
    editButtons.forEach((button, index) => {
        const editor = document.getElementById(`editor-${index}`);
        
        // Toggle editor when clicking pencil icon
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleEditor(editor);
        });
        
        // Set up save button
        const saveButton = editor.querySelector('.save-btn');
        saveButton.addEventListener('click', function() {
            const bookItem = button.closest('.current-reads__item');
            const bookId = bookItem.dataset.bookId;
            const input = editor.querySelector('.page-input');
            const pagesRead = input.value;
            
            // Send AJAX request to update progress
            fetch('/ShelfControl/update-progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `book_id=${bookId}&pages_read=${pagesRead}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const totalPages = parseInt(editor.querySelector('.total-pages').textContent);
                    updateBookProgress(index, pagesRead, totalPages);
                    editor.classList.remove('active');
                    
                    // Show success message
                    showFinishNotification('Progress updated successfully!');
                    
                    // If book is completed (100%), reload page after a delay
                    if (parseInt(pagesRead) >= totalPages) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    alert(data.message || 'Failed to update progress');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
        
        // Set up finish button
        const finishButton = editor.querySelector('.finish-btn');
        finishButton.addEventListener('click', function() {
            const bookItem = button.closest('.current-reads__item');
            const bookId = bookItem.dataset.bookId;
            const totalPages = editor.querySelector('.total-pages').textContent;
            const bookTitle = bookItem.querySelector('.current-reads__book-title').textContent;
            
            // Send AJAX request to mark as finished
            fetch('/ShelfControl/update-progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `book_id=${bookId}&pages_read=${totalPages}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI to show 100%
                    updateBookProgress(index, totalPages, totalPages);
                    editor.classList.remove('active');
                    
                    // Show finish notification
                    showFinishNotification(`"${bookTitle}" marked as finished!`);
                    
                    // Reload page after a delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert(data.message || 'Failed to update progress');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
});

function toggleEditor(editor) {
    // Close all other editors
    document.querySelectorAll('.progress-editor').forEach(ed => {
        if (ed !== editor) {
            ed.classList.remove('active');
        }
    });
    
    // Toggle current editor
    editor.classList.toggle('active');
}

function updateBookProgress(bookIndex, currentPage, totalPages) {
    const bookItem = document.querySelectorAll('.current-reads__item')[bookIndex];
    const progressBar = bookItem.querySelector('.progress-fill');
    const progressText = bookItem.querySelector('.progress-text');
    
    // Calculate percentage
    const percentage = Math.min(Math.round((currentPage / totalPages) * 100), 100);
    
    // Update UI
    progressBar.style.width = `${percentage}%`;
    progressText.textContent = `${percentage}%`;
}

function showFinishNotification(bookTitle) {
    const notification = document.createElement('div');
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = 'var(--first-color)';
    notification.style.color = 'white';
    notification.style.padding = '10px 20px';
    notification.style.borderRadius = '4px';
    notification.style.zIndex = '1000';
    notification.textContent = `"${bookTitle}" marked as finished!`;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}