document.addEventListener("DOMContentLoaded", function () {
    
    const params = new URLSearchParams(window.location.search);
    let query = params.get("query"); 

     if (query==null) {
        const keywords = ['love', 'literature', 'peace', 'history', 'magic', 'adventure', 'mystery', 'science', 'fantasy', 'art', 'life', 'dream'];
        query = keywords[Math.floor(Math.random() * keywords.length)];
    }
    
    loadBooks(query);    // Move event listener inside DOMContentLoaded
    const applyFilterBtn = document.getElementById("apply-filter-btn");
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener("click", function() {
            const authorInput = document.getElementById("filter-author");
            const genreInput = document.getElementById("filter-genre");
            
            const author = authorInput ? authorInput.value.trim() : "";
            const genre = genreInput ? genreInput.value.trim() : "";

            let queryParts = [];

            if (author) {
                queryParts.push(`author=${author}`);
            }

            if (genre) {
                queryParts.push(`genre=${genre}`);
            }

            loadBooksByFilters(queryParts.join('&'));
        });
    }
});

function loadBooksByFilters(filterQuery) {
    const container = document.getElementById("books-container");
    if (!container) {
        console.error("books-container element not found");
        return;
    }
    
    container.innerHTML = "<p>Se încarcă cărțile...</p>";
    console.log("Filtru aplicat:", filterQuery); 
    fetch(`/ShelfControl/filter-books?${filterQuery}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
             if (data.books && data.books.length > 0) {
            window.allBooks = data.books;
            renderBooks(data.books);
            extractAndPopulateGenres(data.books);

                
            } else {
                container.innerHTML = "<p>Nu s-au găsit cărți.</p>";
                 fetch('/ShelfControl/api/libraries')
                        .then(res => res.json())
                        .then(libraries => {
                 
                            container.innerHTML = "<h3>Biblioteci recomandate:</h3>";
                            if (libraries.length === 0) {
                                container.innerHTML += "<p>Nu s-au găsit biblioteci.</p>";
                            } else {
                               console.log("Biblioteci găsite:", libraries);
                                libraries.forEach(lib => {
                                    container.innerHTML += `
                                        <div class="library-card">
                                            <h4>${lib.name}</h4>
                                            <p>${lib.address}</p>
                                        </div>
                                    `;
                                });
                            }
                        })
                        .catch((error) => {
                            container.innerHTML = "<p>Eroare la încărcarea bibliotecilor.</p>";
                        });
            }
        })
        .catch((error) => {
            container.innerHTML = "<p>Eroare la încărcarea cărților.</p>";
              console.error("Eroare la încărcarea bibliotecilor:", error);

        });
}

function loadBooks(query) {
    const container = document.getElementById("books-container");
    if (!container) {
        console.error("books-container element not found");
        return;
    }
    
    container.innerHTML = "<p>Se încarcă cărțile...</p>";

    fetch(`/ShelfControl/search-books?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
             if (data.books && data.books.length > 0) {
            window.allBooks = data.books;
            renderBooks(data.books);
            extractAndPopulateGenres(data.books);

                
            } else {
                container.innerHTML = "<p>Nu s-au găsit cărți.</p>";
                 fetch('/ShelfControl/api/libraries')
                        .then(res => res.json())
                        .then(libraries => {
                    
                            container.innerHTML = "<h3>Biblioteci recomandate:</h3>";
                            if (libraries.length === 0) {
                                container.innerHTML += "<p>Nu s-au găsit biblioteci.</p>";
                            } else {
                               console.log("Biblioteci găsite:", libraries);
                                libraries.forEach(lib => {
                                    container.innerHTML += `
                                        <div class="library-card">
                                            <h4>${lib.name}</h4>
                                            <p>${lib.address}</p>
                                        </div>
                                    `;
                                });
                            }
                        })
                        .catch((error) => {
                            container.innerHTML = "<p>Eroare la încărcarea bibliotecilor.</p>";
                        });
            }
        })
        .catch((error) => {
            container.innerHTML = "<p>Eroare la încărcarea cărților.</p>";
              console.error("Eroare la încărcarea bibliotecilor:", error);

        });
}
function extractAndPopulateGenres(books) {
    const genreSet = new Set();
    
    books.forEach(book => {
        const categories = book.volumeInfo.categories;
        if (categories && Array.isArray(categories)) {
            categories.forEach(category => genreSet.add(category));
        }
    });
     console.log("Genuri extrase:", Array.from(genreSet));


  const genreList = document.getElementById("genre-list");    
  if (genreList) {
        genreList.innerHTML = ""; 
        genreSet.forEach(genre => {
            const option = document.createElement("option");
            option.value = genre;
            option.textContent = genre;
            genreList.appendChild(option);
        });
    }
}


function renderBooks(books) {
    const container = document.getElementById("books-container");
    container.innerHTML = "";

    if (!books || books.length === 0) {
        container.innerHTML = "<p>Nu s-au găsit cărți.</p>";
        return;
    }

    books.forEach((book, index) => {
        const info = book.volumeInfo;
        
        // Check if this is a database book (has numeric ID) or Google Books API book
        const isDbBook = book.id && /^\d+$/.test(book.id.toString());
        const bookLink = isDbBook ? `/ShelfControl/book-details?id=${book.id}` : '#';
        const linkClass = isDbBook ? 'book-card-link' : 'book-card-link-disabled';
        
        container.innerHTML += `
            <div class="book-card" >
                <div class="book-card__cover">
                    <img src="${info.imageLinks?.thumbnail || 'https://upittpress.org/wp-content/themes/pittspress/images/no_cover_available.png'}" alt="Book cover" class="book-card__img">
                </div>
                ${isDbBook ? `<a href="${bookLink}" class="${linkClass}">` : `<div class="${linkClass}" style="cursor: default;">`}
                <div class="book-card__data">
                    <h3 class="book-card__title">${info.title || "Fără titlu"}</h3>
                    <p class="book-card__author">${info.authors ? info.authors.join(", ") : "Autor necunoscut"}</p>
                    <p class="book-card__edition">${info.publishedDate || ""}</p>
                    <div class="book-card__rating">
                        <span>${info.averageRating ? info.averageRating + "★" : "Fără rating"}</span>
                    </div>
                </div>
                ${isDbBook ? '</a>' : '</div>'}
                <div class="book-card__actions">
                    <button class="book-card__btn save-book" data-index="${index}">
                        <i class="ri-bookmark-line"></i>
                    </button>
                    <button class="book-card__btn info-btn" data-index="${index}" title="Descriere: ${(info.description || "Fără descriere").replace(/"/g, "'")}\nAutor: ${info.authors ? info.authors.join(", ") : "Autor necunoscut"}\nPublicat: ${info.publishedDate || ""}">
                        <i class="ri-information-line"></i>
                    </button>
                </div>
            </div>
            
        `;
    });
}


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


document.addEventListener("click", function(e) {
  // Handle save book button
  if (e.target.closest(".save-book")) {
    const btn = e.target.closest(".save-book");
    const index = btn.getAttribute("data-index");
    const book = window.allBooks[index];

    if (!book) {
      alert("Carte inexistentă!");
      return;
    }

    const info = book.volumeInfo;
  
  
    const bookData = {
      title: info.title || null,
      author: info.authors ? info.authors[0] : null, 
      translator: null, 
      publishing_house: info.publisher ||null,
      sub_publisher: null,
      isbn: extractISBN(info.industryIdentifiers),
      publication_year: info.publishedDate ? parseInt(info.publishedDate.substring(0, 4)) : null,
      cover: info.imageLinks?.thumbnail || null,
      language: info.language || null,
      genre: info.categories ? info.categories[0] : null,
      summary: info.description || null,
      pages: info.pageCount || null,
      source: 'Google Books API' ,
      google_id: book.id || null,

    };
      console.log("Verificare SOURCE:", bookData.source); 
  
    const dataToSend = [bookData];    console.log("Trimitem datele formatate:", dataToSend);

    fetch('/ShelfControl/explore', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(dataToSend)
    })
    .then(res => res.text())
    .then(text => {
      console.log("Răspuns server brut:", text);
      try {
        const response = JSON.parse(text);
        alert(response.message || "Carte salvată cu succes!");
      } catch (parseError) {
        console.error("Eroare la parsarea răspunsului:", parseError);
        // Dacă nu putem parsa JSON-ul, afișăm textul brut
        if (text.includes("success") || text.includes("completed")) {
          alert("Carte salvată cu succes!");
        } else {
          alert("Eroare la salvarea cărții: " + text);
        }
      }
    })    .catch(err => {
      alert("Eroare la salvarea cărții.");
      console.error("Eroare fetch:", err);
    });
  }
  
  // Handle info button for external books
  if (e.target.closest(".info-btn")) {
    const btn = e.target.closest(".info-btn");
    const index = btn.getAttribute("data-index");
    const book = window.allBooks[index];

    if (!book) {
      alert("Informații indisponibile!");
      return;
    }

    const info = book.volumeInfo;
    const isDbBook = book.id && /^\d+$/.test(book.id.toString());
    
    if (isDbBook) {
      // For database books, redirect to the book details page
      window.location.href = `/ShelfControl/book-details?id=${book.id}`;
    } else {
      // For external books, show a modal or alert with book info
      const bookInfo = `
Titlu: ${info.title || "Necunoscut"}
Autor: ${info.authors ? info.authors.join(", ") : "Necunoscut"}
Data publicării: ${info.publishedDate || "Necunoscută"}
Editura: ${info.publisher || "Necunoscută"}
Pagini: ${info.pageCount || "Necunoscut"}
Rating: ${info.averageRating || "Fără rating"}
Descriere: ${info.description ? info.description.substring(0, 200) + "..." : "Fără descriere"}
      `;
      alert(bookInfo);
    }
  }
});

function extractISBN(industryIdentifiers) {
  if (!industryIdentifiers || !Array.isArray(industryIdentifiers)) {
    return null;
  }
  
  const isbn13 = industryIdentifiers.find(id => id.type === 'ISBN_13');
  if (isbn13) return isbn13.identifier;
  
  const isbn10 = industryIdentifiers.find(id => id.type === 'ISBN_10');
  if (isbn10) return isbn10.identifier;
  
  return null;
}