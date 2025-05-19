
    async function fetchBooks(query ="*") {
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
                        <div class="book-card">
                            <div class="book-cover">
                                <img src="${book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : 'https://via.placeholder.com/150'}" width="100" height="100" alt="Coperta cărții">
                            </div>
                            <div class="book-info">
                                <h2 class="book-title"><a href="book-details.html?id=${book.id}">${book.volumeInfo.title}</a></h2>
                                <p class="book-pages">${book.volumeInfo.pageCount ? `Pages: ${book.volumeInfo.pageCount}` : 'Page count unavailable'}</p>
                                <p class="book-author">${book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : 'Unknown author'}</p>
                            </div>
                            <div class="book-actions">
                                <button class="add-to-favorite">to read</button>
                                <button class="buy-now">mark as own</button>
                            </div>
                        </div>
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

function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}


window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

