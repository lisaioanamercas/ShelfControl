document.addEventListener('DOMContentLoaded', function() {
    // Function to show loader and hide content
    function showLoader(loaderId, contentId) {
        const loader = document.getElementById(loaderId);
        const content = document.getElementById(contentId);
        
        if (loader && content) {
            // Make loader visible first for smooth transition
            loader.style.display = 'block';
            setTimeout(() => {
                loader.classList.add('active');
                content.style.display = 'none';
            }, 0);
        }
    }
    
    // Function to hide loader and show content
    function hideLoader(loaderId, contentId) {
        const loader = document.getElementById(loaderId);
        const content = document.getElementById(contentId);
        
        if (loader && content) {
            loader.classList.remove('active');
            content.style.display = 'block';
            
            // Fix for book grid - restore grid properties
            const bookGrid = content.querySelector('.book-grid');
            if (bookGrid) {
                bookGrid.style.display = 'grid';
            }
            
            // Fix for Currently Reading section
            if (contentId === 'current-reading-content') {
                content.style.display = 'grid'; // Set the container itself to grid
            }
        }
    }
    
    // Handle loaders on home page
    if (document.getElementById('toread-loader')) {
        showLoader('toread-loader', 'toread-content');
        setTimeout(() => hideLoader('toread-loader', 'toread-content'), 1500);
    }
    
    if (document.getElementById('owned-loader')) {
        showLoader('owned-loader', 'owned-content');
        setTimeout(() => hideLoader('owned-loader', 'owned-content'), 1000);
    }
    
    if (document.getElementById('read-loader')) {
        showLoader('read-loader', 'read-content');
        setTimeout(() => hideLoader('read-loader', 'read-content'), 1200);
    }
    
    // Handle loader on book collection pages
    if (document.getElementById('books-loader')) {
        showLoader('books-loader', 'books-content');
        setTimeout(() => hideLoader('books-loader', 'books-content'), 800);
    }
    
    // For current reading section on home page
    if (document.getElementById('current-reading-loader')) {
        showLoader('current-reading-loader', 'current-reading-content');
        setTimeout(() => hideLoader('current-reading-loader', 'current-reading-content'), 600);
    }
});