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
        if (statusBtn && !statusBtn.contains(e.target) && !statusOptions.contains(e.target)) {
            statusOptions.classList.remove('active');
        }
    });
    
    // Select status option
    const statusOptionElements = document.querySelectorAll('.status-option');
    statusOptionElements.forEach(option => {
        option.addEventListener('click', () => {
            const selectedStatus = option.getAttribute('data-status');
            const bookId = document.querySelector('.owned-btn').getAttribute('data-book-id');
            
            // Update UI
            currentStatus.textContent = selectedStatus;
            statusOptions.classList.remove('active');
            
            // Show reading progress if status is "reading"
            if (selectedStatus === 'reading') {
                readingProgress.classList.add('active');
            } else {
                readingProgress.classList.remove('active');
            }
            
            // Send status update to server
            updateBookStatus(bookId, selectedStatus);
        });
    });
    
    // Update reading progress
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    if (saveProgressBtn) {
        saveProgressBtn.addEventListener('click', () => {
            const bookId = saveProgressBtn.getAttribute('data-book-id');
            const currentPageInput = document.getElementById('currentPageInput');
            const totalPages = document.getElementById('totalPages');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            
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
            
            // Send progress update to server
            updateBookProgress(bookId, currentPage);
        });
    }
    
    // Toggle owned status
    const ownedBtn = document.getElementById('ownedBtn');
    if (ownedBtn) {
        ownedBtn.addEventListener('click', () => {
            const bookId = ownedBtn.getAttribute('data-book-id');
            const isOwned = ownedBtn.classList.contains('active');
            
            // Toggle active state
            ownedBtn.classList.toggle('active');
            
            // Update owned status on server
            updateBookOwned(bookId, !isOwned);
        });
    }
    
    // Buy button
    const buyBtn = document.getElementById('buyBtn');
    if (buyBtn) {
        buyBtn.addEventListener('click', () => {
            // Open a modal or redirect to a purchase page
            alert("This would redirect to a purchase page in a real application");
        });
    }
    
    function updateBookStatus(bookId, status) {


        
        fetch('/ShelfControl/update-book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=status&book_id=${bookId}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating status:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateBookProgress(bookId, pagesRead) {
        fetch('/ShelfControl/update-book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=progress&book_id=${bookId}&pages_read=${pagesRead}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating progress:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateBookOwned(bookId, isOwned) {
        fetch('/ShelfControl/update-book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=owned&book_id=${bookId}&is_owned=${isOwned ? 'Y' : 'N'}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating owned status:', data.message);
                // Revert UI change if server update failed
                ownedBtn.classList.toggle('active');
            }
        })
        .catch(error => console.error('Error:', error));
    }
});