document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("books-container");
    const params = new URLSearchParams(window.location.search);
    const query = params.get("query") || "love,peace,war,comedy"; 

    fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=30`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = ""; 
            if (data.items) {
                window.allBooks = data.items;  // <-- aici salvezi toate cărțile
                data.items.forEach((book, index) => {
                    const info = book.volumeInfo;
                    container.innerHTML += `
                        <div class="book-card">
                            <div class="book-card__cover">
                                <img src="${info.imageLinks?.thumbnail || 'assets/img/default-book.png'}" alt="Book cover" class="book-card__img">
                            </div>
                            <div class="book-card__data">
                                <h3 class="book-card__title">${info.title || "No title"}</h3>
                                <p class="book-card__author">${info.authors ? info.authors.join(", ") : "Unknown author"}</p>
                                <p class="book-card__edition">${info.publishedDate || ""}</p>
                                <div class="book-card__rating">
                                    <span>${info.averageRating ? info.averageRating + "★" : "No rating"}</span>
                                </div>
                            </div>
                            <div class="book-card__actions">
                                <button 
                                    class="book-card__btn save-book" data-index="${index}">  <!-- pui index numeric aici -->
                                    <i class="ri-bookmark-line"></i>
                                </button>
                                <button 
                                    class="book-card__btn"
                                    title="Descriere: ${(info.description || "Fără descriere").replace(/"/g, "'")}\nAutor: ${info.authors ? info.authors.join(", ") : "Unknown author"}\nPublicat: ${info.publishedDate || ""}">
                                    <i class="ri-information-line"></i>
                                </button>
                          </div>
                        </div>
                    `;
                });
            } else {
                container.innerHTML = "<p>No books found.</p>";
            }
        })
        .catch(() => {
            container.innerHTML = "<p>Failed to load books.</p>";
        });
});


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
      pages: info.pageCount || null
    };

  
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



