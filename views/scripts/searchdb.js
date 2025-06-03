document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('db-search-form');
    const searchInput = document.getElementById('db-search-input');
    const resultsContainer = document.getElementById('search-results-container');
    const booksContainer = document.getElementById('books-container');

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            
            if (query.length > 0) {
                performSearch(query);
            }
        });
    }
    
    function performSearch(query) {
        // Show loading indicator
        resultsContainer.innerHTML = '<div class="loading">Searching...</div>';
        
        fetch(`/ShelfControl/searchdb?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.results.length > 0) {
                    displayResults(data.results);
                } else {
                    resultsContainer.innerHTML = '<p class="no-results">No books found matching your search.</p>';
                }
            })
            .catch(error => {
                console.error('Error searching:', error);
                resultsContainer.innerHTML = '<p class="search-error">An error occurred. Please try again.</p>';
            });
    }
    
    function displayResults(books) {
        resultsContainer.innerHTML = '';
        
        const header = document.createElement('div');
        header.className = 'results-header';
        header.innerHTML = `<h3>Search Results (${books.length})</h3>
                           <button id="close-search" class="close-search">Close</button>`;
        resultsContainer.appendChild(header);
        
        const grid = document.createElement('div');
        grid.className = 'book-grid';
        
        books.forEach(book => {
            const bookElement = createBookElement(book);
            grid.appendChild(bookElement);
        });
        
        resultsContainer.appendChild(grid);
        
        // Show results and hide normal book list
        resultsContainer.style.display = 'block';
        booksContainer.style.display = 'none';
        
        // Add close button functionality
        document.getElementById('close-search').addEventListener('click', () => {
            resultsContainer.style.display = 'none';
            booksContainer.style.display = 'block';
        });
    }
    
    function createBookElement(book) {
        const bookItem = document.createElement('div');
        bookItem.className = 'book-item';
        
        bookItem.innerHTML = `
            <a href="/ShelfControl/book-details?id=${book.BOOK_ID}">
                <img src="${book.COVER_URL || '/ShelfControl/assets/images/default-book.png'}" alt="${book.TITLE}" class="book-item__img">
                <div class="book-item__info">
                    <h3 class="book-item__title">${book.TITLE}</h3>
                    <p class="book-item__author">${book.AUTHOR_NAME}</p>
                </div>
            </a>
        `;
        
        return bookItem;
    }
});