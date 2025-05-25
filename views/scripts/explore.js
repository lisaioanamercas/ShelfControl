document.addEventListener("DOMContentLoaded", function () {
    
    const params = new URLSearchParams(window.location.search);
    const query = params.get("query") || "love,history,science"; 
    loadBooks(query);
});

document.getElementById("apply-filter-btn").addEventListener("click", function() {
    const author = document.getElementById("filter-author").value.trim();
    const genre = document.getElementById("filter-genre").value.trim();

  
    let queryParts = [];

    const params = new URLSearchParams(window.location.search);
    const queryFromUrl = params.get("query") || "*"; 
    if (queryFromUrl && queryFromUrl !== "*") {
        queryParts.push(queryFromUrl);
    }

    if (author) {
        queryParts.push(`inauthor:${author}`);
    }

    if (genre) {
        queryParts.push(`subject:${genre}`);
    }

    
    let query = queryParts.length > 0 ? queryParts.join('+') : '*';

    loadBooks(query);
});

function loadBooks(query) {
    const container = document.getElementById("books-container");
    container.innerHTML = "<p>Se încarcă cărțile...</p>";

    fetch(`https://www.googleapis.com/books/v1/volumes?q=intitle:${encodeURIComponent(query)}&maxResults=40`)
        .then(response => response.json())
        .then(data => {
            if (data.items) {
                window.allBooks = data.items;
                renderBooks(data.items);
            } else {
                container.innerHTML = "<p>Nu s-au găsit cărți.</p>";
            }
        })
        .catch(() => {
            container.innerHTML = "<p>Eroare la încărcarea cărților.</p>";
        });
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
        container.innerHTML += `
            <div class="book-card" >
                <div class="book-card__cover">
                    <img src="${info.imageLinks?.thumbnail || 'assets/img/default-book.png'}" alt="Book cover" class="book-card__img">
                </div>
                <a href="/ShelfControl/book-details?id=${book.id}" class="book-card-link">
                <div class="book-card__data">
                    <h3 class="book-card__title">${info.title || "Fără titlu"}</h3>
                    <p class="book-card__author">${info.authors ? info.authors.join(", ") : "Autor necunoscut"}</p>
                    <p class="book-card__edition">${info.publishedDate || ""}</p>
                    <div class="book-card__rating">
                        <span>${info.averageRating ? info.averageRating + "★" : "Fără rating"}</span>
                    </div>
                </div>
                </a>
                <div class="book-card__actions">
                    <button class="book-card__btn save-book" data-index="${index}">
                        <i class="ri-bookmark-line"></i>
                    </button>
                    <button class="book-card__btn" title="Descriere: ${(info.description || "Fără descriere").replace(/"/g, "'")}\nAutor: ${info.authors ? info.authors.join(", ") : "Autor necunoscut"}\nPublicat: ${info.publishedDate || ""}">
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
      source: 'Google Books API' 
    };
      console.log("Verificare SOURCE:", bookData.source); 
  
    const dataToSend = [bookData];

    console.log("Trimitem datele formatate:", dataToSend);

    fetch('ShelfControl/explore', {
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
    })
    .catch(err => {
      alert("Eroare la salvarea cărții.");
      console.error("Eroare fetch:", err);
    });
  }
});

// Funcție helper pentru extragerea ISBN
function extractISBN(industryIdentifiers) {
  if (!industryIdentifiers || !Array.isArray(industryIdentifiers)) {
    return null;
  }
  
  // Căutăm ISBN_13 mai întâi, apoi ISBN_10
  const isbn13 = industryIdentifiers.find(id => id.type === 'ISBN_13');
  if (isbn13) return isbn13.identifier;
  
  const isbn10 = industryIdentifiers.find(id => id.type === 'ISBN_10');
  if (isbn10) return isbn10.identifier;
  
  return null;
}

function extractISBN(identifiers) {
  if (!identifiers || !Array.isArray(identifiers)) return "";
  const isbn = identifiers.find(id => id.type === "ISBN_13") || identifiers.find(id => id.type === "ISBN_10");
  return isbn ? isbn.identifier : "";
}



