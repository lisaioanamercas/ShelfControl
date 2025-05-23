
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("books-container");
    const params = new URLSearchParams(window.location.search);
    const query = params.get("query") || "love";

fetch(`https://openlibrary.org/search.json?q=${encodeURIComponent(query)}&limit=50`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = "";
            if (data.docs && data.docs.length > 0) {
                window.allBooks = data.docs;
                data.docs.forEach((book, index) => {
                    const coverUrl = book.cover_i ? 
                        `https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg` : 
                        'assets/img/default-book.png';
                    container.innerHTML += `
                        <div class="book-card">
                            <div class="book-card__cover">
                                <img src="${coverUrl}" alt="Book cover" class="book-card__img">
                            </div>
                            <div class="book-card__data">
                                <h3 class="book-card__title">${book.title || "No title"}</h3>
                                <p class="book-card__author">${book.author_name ? book.author_name.join(", ") : "Unknown author"}</p>
                                <p class="book-card__edition">${book.first_publish_year || ""}</p>
                                <div class="book-card__rating">
                                    <span>No rating</span>
                                </div>
                            </div>
                            <div class="book-card__actions">
                                <button class="book-card__btn save-book" data-index="${index}">
                                    <i class="ri-bookmark-line"></i>
                                </button>
                                <button class="book-card__btn" title="Autor: ${book.author_name?.join(", ") || "Unknown"}\nPublicat: ${book.first_publish_year || ""}">
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

document.addEventListener("click", function(e) {
    if (e.target.closest(".save-book")) {
        const btn = e.target.closest(".save-book");
        const index = btn.getAttribute("data-index");
        const book = window.allBooks[index];

        if (!book) {
            alert("Carte inexistentă!");
            return;
        }

        const bookData = {
            title: book.title || null,
            author: book.author_name ? book.author_name[0] : null,
            translator: null,
            publishing_house: book.publisher ? book.publisher[0] : null,
            sub_publisher: null,
            isbn: book.isbn ? book.isbn[0] : null,
            publication_year: book.first_publish_year || null,
            cover: book.cover_i ? `https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg` : null,
            language: book.language ? book.language[0] : null,
            genre: null,
            summary: null,
            pages: null,
            source: "Open Library API"
        };

        const dataToSend = [bookData];

        fetch('ShelfControl/explore', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataToSend)
        })
        .then(res => res.text())
        .then(text => {
            try {
                const response = JSON.parse(text);
                alert(response.message || "Carte salvată cu succes!");
            } catch {
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

