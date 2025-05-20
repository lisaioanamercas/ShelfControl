document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("books-container");
    const params = new URLSearchParams(window.location.search);
    const query = params.get("query") || "love,peace,war,comedy"; // valoare implicită dacă nu e în URL

    fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=30`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = ""; // Golește containerul
            if (data.items) {
                data.items.forEach(book => {
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
                                    class="book-card__btn save-book"
                                    data-title="${info.title || ''}" 
                                    data-author="${info.authors ? info.authors.join(', ') : 'Unknown'}" 
                                    data-date="${info.publishedDate || ''}">
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

window.onclick = function(event) {
  if (!event.target.matches('.book-card__btn')) {
      
    }
   
  }



