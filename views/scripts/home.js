document.addEventListener('DOMContentLoaded', function() {
    console.log('Home.js loaded - initializing edit buttons');
    

    document.addEventListener('click', function(event) {

        if (event.target.closest('.edit-progress-btn')) {
            console.log('Edit button clicked');
            const button = event.target.closest('.edit-progress-btn');
            const bookItem = button.closest('.current-reads__item');
            const editorId = bookItem.querySelector('.progress-editor').id;
            const editor = document.getElementById(editorId);
            
            if (editor) {
                event.stopPropagation();
                toggleEditor(editor);
            }
        }
        
       
        if (event.target.closest('.save-btn')) {
            console.log('Save button clicked');
            event.preventDefault();
            const saveBtn = event.target.closest('.save-btn');
            const editor = saveBtn.closest('.progress-editor');
            const bookItem = editor.closest('.current-reads__item');
            const bookId = bookItem.dataset.bookId;
            const input = editor.querySelector('.page-input');
            const pagesRead = input.value;
            
            const allBookItems = Array.from(document.querySelectorAll('.current-reads__item'));
            const bookIndex = allBookItems.indexOf(bookItem);
            
   
            fetch('/ShelfControl/update-book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=progress&book_id=${bookId}&pages_read=${pagesRead}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                
                    const totalPages = parseInt(editor.querySelector('.total-pages').textContent);
                    updateBookProgress(bookIndex, pagesRead, totalPages);
                    editor.classList.remove('active');
                    
                
                    showFinishNotification('Progress updated successfully!');
                    
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
        }
        

        if (event.target.closest('.finish-btn')) {
            console.log('Finish button clicked');
            event.preventDefault();
            const finishBtn = event.target.closest('.finish-btn');
            const editor = finishBtn.closest('.progress-editor');
            const bookItem = editor.closest('.current-reads__item');
            const bookId = bookItem.dataset.bookId;
            const totalPages = editor.querySelector('.total-pages').textContent;
            const bookTitle = bookItem.querySelector('.current-reads__book-title').textContent;
            
    
            const allBookItems = Array.from(document.querySelectorAll('.current-reads__item'));
            const bookIndex = allBookItems.indexOf(bookItem);
            

            fetch('/ShelfControl/update-book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=progress&book_id=${bookId}&pages_read=${pagesRead}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
              
                    updateBookProgress(bookIndex, totalPages, totalPages);
                    editor.classList.remove('active');
                   
                    showFinishNotification(`"${bookTitle}" marked as finished!`);
                    
            
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
        }
        

        const isEditor = event.target.closest('.progress-editor');
        const isEditButton = event.target.closest('.edit-progress-btn');
        
        if (!isEditor && !isEditButton) {
            document.querySelectorAll('.progress-editor').forEach(editor => {
                editor.classList.remove('active');
            });
        }
    });
});

function toggleEditor(editor) {
    console.log('Toggling editor:', editor.id);
    

    document.querySelectorAll('.progress-editor').forEach(ed => {
        if (ed !== editor) {
            ed.classList.remove('active');
        }
    });
    
 
    editor.classList.toggle('active');
}

function updateBookProgress(bookIndex, currentPage, totalPages) {
    const bookItem = document.querySelectorAll('.current-reads__item')[bookIndex];
    const progressBar = bookItem.querySelector('.progress-fill');
    const progressText = bookItem.querySelector('.progress-text');
    
 
    const percentage = Math.min(Math.round((currentPage / totalPages) * 100), 100);
 
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
    notification.textContent = `${bookTitle}`;
    
    document.body.appendChild(notification);
    
 
    setTimeout(() => {
        notification.remove();
    }, 3000);
}