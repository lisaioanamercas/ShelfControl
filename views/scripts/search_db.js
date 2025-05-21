document.addEventListener('DOMContentLoaded', () => {
    // Search functionality
    const searchButton = document.getElementById('search-button');
    const searchClose = document.getElementById('search-close');
    const searchContent = document.getElementById('search-content');
    const searchForm = document.querySelector('.search__form');
    const searchInput = document.querySelector('.search__input');
    const mainContent = document.querySelector('.main');
    
    // Toggle search box
    if (searchButton) {
        searchButton.addEventListener('click', () => {
            searchContent.classList.add('show-search');
            searchInput.focus();
        });
    }
    
    if (searchClose) {
        searchClose.addEventListener('click', () => {
            searchContent.classList.remove('show-search');
            resetSearch();
        });
    }
    
    // Handle search form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            
            if (query.length > 0) {
                performSearch(query);
            }
        });
    }
    
    // Reset search and show original page content
    function resetSearch() {
        searchInput.value = '';
        if (document.getElementById('search-results')) {
            document.getElementById('search-results').remove();
            mainContent.style.display = 'block';
        }
    }
    
    // Perform search against the database
    function performSearch(query) {
        // Show loading indicator
        showLoading();
        
        fetch(`/ShelfControl/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                hideLoading();
                displaySearchResults(data.results);
            })
            .catch(error => {
                hideLoading();
                console.error('Error performing search:', error);
                showError('An error occurred while searching. Please try again.');
            });
    }
    
    // Display search results
    function displaySearchResults(results) {
        // Hide the main content
        mainContent.style.display = 'none';
        
        // Remove any existing search results
        if (document.getElementById('search-results')) {
            document.getElementById('search-results').remove();
        }
        
        // Create search results container
        const searchResults = document.createElement('div');
        searchResults.id = 'search-results';
        searchResults.className = 'search-results section container';
        
        // Create header
        const header = document.createElement('div');
        header.className = 'search-results__header';
        
        const title = document.createElement('h2');
        title.className = 'search-results__title';
        title.textContent = `Search Results (${results.length})`;
        
        const closeButton = document.createElement('button');
        closeButton.className = 'search-results__close';
        closeButton.innerHTML = '<i class="ri-close-line"></i>';
        closeButton.addEventListener('click', resetSearch);
        
        header.appendChild(title);
        header.appendChild(closeButton);
        searchResults.appendChild(header);
        
        if (results.length === 0) {
            const noResults = document.createElement('p');
            noResults.className = 'search-results__empty';
            noResults.textContent = 'No books found matching your search.';
            searchResults.appendChild(noResults);
        } else {
            // Create grid for results
            const grid = document.createElement('div');
            grid.className = 'book-grid';
            
            // Add each book to grid
            results.forEach(book => {
                const bookItem = document.createElement('div');
                bookItem.className = 'book-item';
                
                const link = document.createElement('a');
                link.href = `/ShelfControl/book-details?id=${book.BOOK_ID}`;
                
                const img = document.createElement('img');
                img.src = book.COVER_URL || 'assets/img/default-book.png';
                img.alt = book.TITLE;
                img.className = 'book-item__img';
                
                link.appendChild(img);
                bookItem.appendChild(link);
                
                // Add book info
                const info = document.createElement('div');
                info.className = 'book-item__info';
                
                const bookTitle = document.createElement('h3');
                bookTitle.className = 'book-item__title';
                bookTitle.textContent = book.TITLE;
                
                const bookAuthor = document.createElement('p');
                bookAuthor.className = 'book-item__author';
                bookAuthor.textContent = book.AUTHOR_NAME;
                
                info.appendChild(bookTitle);
                info.appendChild(bookAuthor);
                
                if (book.reading_status) {
                    const status = document.createElement('span');
                    status.className = `book-status ${book.reading_status}`;
                    status.textContent = book.reading_status;
                    info.appendChild(status);
                }
                
                bookItem.appendChild(info);
                grid.appendChild(bookItem);
            });
            
            searchResults.appendChild(grid);
        }
        
        // Add search results to page
        document.body.insertBefore(searchResults, mainContent.nextSibling);
        
        // Close search input box
        searchContent.classList.remove('show-search');
    }
    
    // Show loading indicator
    function showLoading() {
        const loading = document.createElement('div');
        loading.id = 'search-loading';
        loading.className = 'search-loading';
        loading.innerHTML = '<i class="ri-loader-4-line"></i>';
        document.body.appendChild(loading);
    }
    
    // Hide loading indicator
    function hideLoading() {
        const loading = document.getElementById('search-loading');
        if (loading) {
            loading.remove();
        }
    }
    
    // Show error message
    function showError(message) {
        const error = document.createElement('div');
        error.className = 'search-error';
        error.textContent = message;
        
        document.body.appendChild(error);
        
        setTimeout(() => {
            error.remove();
        }, 3000);
    }
});