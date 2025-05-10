
    async function fetchBooks(query ="romance") {
        const apiUrl = `https://www.googleapis.com/books/v1/volumes?q=${query}&maxResults=30`;
        try {
            const response = await fetch(apiUrl);
            const data = await response.json();
            
            const bookList = document.getElementById('book-list');
            bookList.innerHTML = ''; // Clear existing list
            // Check if books were found
            if (data.items) {
                data.items.forEach(book => {
                    const bookItem = document.createElement('div');
                    bookItem.classList.add('book-item');
                    bookItem.innerHTML = `
                        <h3>${book.volumeInfo.title}</h3>
                        <p>${book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : 'Unknown author'}</p>
                        <img src="${book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : 'https://via.placeholder.com/150'}" alt="${book.volumeInfo.title}" />
                    `;
                    bookList.appendChild(bookItem);
                });
            } else {
                bookList.innerHTML = '<p>No books found</p>';
            }
        } catch (error) {
            console.error('Error fetching books:', error);
            document.getElementById('book-list').innerHTML = '<p>Failed to load books</p>';
        }
    }

        // Fetch books when the page loads
        window.onload = () => {
            fetchBooks(); // You can pass a search term if needed
        };
