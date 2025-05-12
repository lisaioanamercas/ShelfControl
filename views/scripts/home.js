
    async function fetchBooks(query ="love") {
        const apiUrl = `https://www.googleapis.com/books/v1/volumes?q=${query}&maxResults=30`;
        try {
            const response = await fetch(apiUrl);
            const data = await response.json();
            
            const bookList = document.getElementById('book-list');
            bookList.innerHTML = ''; 
           
            if (data.items) {
                data.items.forEach(book => {
                    const bookItem = document.createElement('div');
                    bookItem.classList.add('book-item');
                    bookItem.innerHTML = `
                        <h3><a href="book-details.html?id=${book.id}">${book.volumeInfo.title}</a></h3>
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

        window.onload = () => {
            fetchBooks();
        };
