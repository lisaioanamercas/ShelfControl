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
    // ================ HANDLE HOME PAGE LOADERS ================

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
    
    // For current reading section on home page
    if (document.getElementById('current-reading-loader')) {
        showLoader('current-reading-loader', 'current-reading-content');
        setTimeout(() => hideLoader('current-reading-loader', 'current-reading-content'), 600);
    }

        // ================ HANDLE CATEGORIZED BOOKS PAGE LOADERS ================
    // Special case for categorizedBooksDisplay.tpl where we have mismatched IDs
    const loaderContentPairs = [
        // Format: [loaderId, contentId]
        ['books-loader', 'toread-books-content'],  // To Read section
        ['books-loader', 'owned-books-content']    // Owned Books section
    ];
    
    // Process all loader-content pairs that exist on the page
    loaderContentPairs.forEach(pair => {
        const [loaderId, contentId] = pair;
        const loader = document.getElementById(loaderId);
        const content = document.getElementById(contentId);
        
        // Only process if both elements exist
        if (loader && content) {
            // Find which loader this is (first or second instance)
            const allLoaders = document.querySelectorAll(`#${loaderId}`);
            const loaderIndex = Array.from(allLoaders).indexOf(loader);
            
            // Only process if this is the first loader with this ID matching this content
            if (loaderIndex === 0 && contentId === 'toread-books-content') {
                showLoader(loaderId, contentId);
                setTimeout(() => hideLoader(loaderId, contentId), 1200);
            }
            // Process second loader with the same ID
            else if (loaderIndex === 1 && contentId === 'owned-books-content') {
                // Use querySelector to get the second loader with the same ID
                const secondLoader = document.querySelectorAll(`#${loaderId}`)[1];
                if (secondLoader) {
                    secondLoader.style.display = 'block';
                    secondLoader.classList.add('active');
                    content.style.display = 'none';
                    
                    setTimeout(() => {
                        secondLoader.classList.remove('active');
                        content.style.display = 'block';
                        
                        // Fix grid display
                        const bookGrid = content.querySelector('.book-grid');
                        if (bookGrid) {
                            bookGrid.style.display = 'grid';
                        }
                    }, 1500);
                }
            }
        }
    });
});