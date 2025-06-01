document.addEventListener('DOMContentLoaded', function() {
    // function showLoader(loaderId, contentId) {
    //     const loader = document.getElementById(loaderId);
    //     const content = document.getElementById(contentId);
    //     if (loader && content) {
    //         loader.style.display = 'block';
    //         loader.classList.add('active');
    //         content.style.display = 'none';
    //     }
    // }

    // function hideLoader(loaderId, contentId) {
    //     const loader = document.getElementById(loaderId);
    //     const content = document.getElementById(contentId);
    //     if (loader && content) {
    //         loader.classList.remove('active');
    //         loader.style.display = 'none';
    //         content.style.display = 'block';
    //         const bookGrid = content.querySelector('.book-grid');
    //         if (bookGrid) bookGrid.style.display = 'grid';
    //         if (contentId === 'current-reading-content') content.style.display = 'grid';
    //     }
    // }
    function showLoader(loaderId, contentId) {
        const loader = document.getElementById(loaderId);
        const content = document.getElementById(contentId);
        if (loader && content) {
            loader.classList.add('active');
            content.style.display = 'none';
        }
    }

    function hideLoader(loaderId, contentId) {
        const loader = document.getElementById(loaderId);
        const content = document.getElementById(contentId);
        if (loader && content) {
            loader.classList.remove('active');
            // This next line is critical - it hides the loader element!
            loader.style.display = 'none'; 
            content.style.display = 'block';
            
            // Fix for book grid layouts
            const bookGrid = content.querySelector('.book-grid');
            if (bookGrid) {
                bookGrid.style.display = 'grid';
            }
            
            // Handle current reading section specially
            if (contentId === 'current-reading-content') {
                content.style.display = 'grid';
            }
        }
    }

    // Home page
    if (document.getElementById('toread-loader')) {
        showLoader('toread-loader', 'toread-content');
        setTimeout(() => hideLoader('toread-loader', 'toread-content'), 1200);
    }
    if (document.getElementById('owned-loader')) {
        showLoader('owned-loader', 'owned-content');
        setTimeout(() => hideLoader('owned-loader', 'owned-content'), 1200);
    }
    if (document.getElementById('read-loader')) {
        showLoader('read-loader', 'read-content');
        setTimeout(() => hideLoader('read-loader', 'read-content'), 1200);
    }
    // if (document.getElementById('current-reading-loader')) {
    //     showLoader('current-reading-loader', 'current-reading-content');
    //     setTimeout(() => hideLoader('current-reading-loader', 'current-reading-content'), 1200);
    // }

    // UserBooksDisplay
    if (document.getElementById('userbooks-loader')) {
        showLoader('userbooks-loader', 'userbooks-content');
        setTimeout(() => hideLoader('userbooks-loader', 'userbooks-content'), 1200);
    }

    // CategorizedBooksDisplay
    if (document.getElementById('toread-books-loader')) {
        showLoader('toread-books-loader', 'toread-books-content');
        setTimeout(() => hideLoader('toread-books-loader', 'toread-books-content'), 1200);
    }
    if (document.getElementById('owned-books-loader')) {
        showLoader('owned-books-loader', 'owned-books-content');
        setTimeout(() => hideLoader('owned-books-loader', 'owned-books-content'), 1200);
    }
    if (document.getElementById('read-books-loader')) {
        showLoader('read-books-loader', 'read-books-content');
        setTimeout(() => hideLoader('read-books-loader', 'read-books-content'), 1200);
    }
});