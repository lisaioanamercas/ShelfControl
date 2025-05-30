document.addEventListener('DOMContentLoaded', () => {
    // Status dropdown functionality
    const statusBtn = document.getElementById('statusBtn');
    const statusOptions = document.getElementById('statusOptions');
    const currentStatus = document.getElementById('currentStatus');
    const readingProgress = document.getElementById('readingProgress');
    const authorElement = document.querySelector('.book-author');
    const titleElement = document.querySelector('.book-title');
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const reviewFormOverlay = document.getElementById('reviewFormOverlay');
    const closeReviewForm = document.getElementById('closeReviewForm');
    const reviewForm = document.getElementById('reviewForm');


     
     if (writeReviewBtn && reviewFormOverlay) {
        writeReviewBtn.addEventListener('click', () => {
            reviewFormOverlay.classList.add('active');
        });
    }

    if (closeReviewForm && reviewFormOverlay) {
        closeReviewForm.addEventListener('click', () => {
            reviewFormOverlay.classList.remove('active');
        });
    }

    // Optional: close overlay when clicking outside the form
    if (reviewFormOverlay) {
        reviewFormOverlay.addEventListener('click', (e) => {
            if (e.target === reviewFormOverlay) {
                reviewFormOverlay.classList.remove('active');
            }
        });
    }
    if(reviewForm)
    {
         reviewForm.addEventListener('submit', function(e) {

            const rating = document.getElementById('ratingValue').value;
            const reviewText = document.getElementById('reviewContent').value;
            const bookId = document.querySelector('.owned-btn')?.getAttribute('data-book-id');

             if (!rating || rating < 1) {
                alert('Selectează un rating.');
                return;
            }

            fetch('/ShelfControl/add-review', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                    body: `book_id=${encodeURIComponent(bookId)}&rating=${encodeURIComponent(rating)}&review_text=${encodeURIComponent(reviewText)}`  })
              .then(response => response.json())
                        .then(data => {
                            // Afișează mesajul de la server
                            if (data.success) {
                                alert(data.message || 'Review adăugat cu succes!');
                                reviewForm.reset();
                                reviewFormOverlay.classList.remove('active');
                            } else {
                                alert(data.message || 'A apărut o eroare!');
                            }
                        })

            e.preventDefault();
            console.log('Review submitted');});
    }
     
    const author = authorElement.innerText;
    console.log('Autor:', author);
        
        
    const currentTitle = titleElement.innerText;
    console.log('Titlu:', currentTitle);

    const bookSummary = document.getElementById('bookSummary');
    const readMoreBtn = document.getElementById('readMoreBtn');
    
    //chestie relativ useless dar face sa arate bine
    if (bookSummary && readMoreBtn) {
        // Get short and full description paragraphs
        const shortDescP = bookSummary.querySelector('p:first-child');
        const fullDescP = bookSummary.querySelector('.full-description');
        
        if (shortDescP && fullDescP) {
            // Add click event listener to the read more link
            readMoreBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Replace the short description with the full description
                shortDescP.innerHTML = fullDescP.innerHTML;
                
                // Hide the read more button
                readMoreBtn.style.display = 'none';
            });
        }
    }
    
    // Toggle status dropdown
    if (statusBtn) {
        statusBtn.addEventListener('click', () => {
            statusOptions.classList.toggle('active');
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (statusBtn && !statusBtn.contains(e.target) && !statusOptions.contains(e.target)) {
            statusOptions.classList.remove('active');
        }
    });
    


          
        
   
});
    
    // Select status option -- am renuntat la functia asta ca nu lua id ul bine
    // const statusOptionElements = document.querySelectorAll('.status-option');
    // statusOptionElements.forEach(option => {
    //     option.addEventListener('click', () => {
    //         const selectedStatus = option.getAttribute('data-status');
    //         const bookId = document.querySelector('.owned-btn').getAttribute('data-book-id');
            
    //         // Update UI
    //         currentStatus.textContent = selectedStatus;
    //         statusOptions.classList.remove('active');
            
    //         // Show reading progress if status is "reading"
    //         if (selectedStatus === 'reading') {
    //             readingProgress.classList.add('active');
    //         } else {
    //             readingProgress.classList.remove('active');
    //         }
            
    //         // Send status update to server
    //         updateBookStatus(bookId, selectedStatus);
    //     });
    // });

    // Select status option
    const statusOptionElements = document.querySelectorAll('.status-option');
    statusOptionElements.forEach(option => {
        option.addEventListener('click', () => {
            const selectedStatus = option.getAttribute('data-status');
            
            // Get book ID from URL first (more reliable)
            let bookId;
            const urlParams = new URLSearchParams(window.location.search);
            bookId = urlParams.get('id');
            
            // If not in URL, try the button attribute as fallback
            if (!bookId) {
                const ownedBtn = document.querySelector('.owned-btn');
                if (ownedBtn) {
                    bookId = ownedBtn.getAttribute('data-book-id');
                }
            }
            
            if (!bookId) {
                console.error('Could not determine book ID');
                return;
            }
            
            // Update UI
            currentStatus.textContent = selectedStatus;
            statusOptions.classList.remove('active');
            
            // Show reading progress if status is "reading"
            if (selectedStatus === 'reading') {
                readingProgress.classList.add('active');
            } else {
                readingProgress.classList.remove('active');
            }
            
            updateBookStatus(bookId, selectedStatus);
        });
    });
    
    // Update reading progress
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    if (saveProgressBtn) {
        saveProgressBtn.addEventListener('click', () => {
            const bookId = saveProgressBtn.getAttribute('data-book-id');
            const currentPageInput = document.getElementById('currentPageInput');
            const totalPages = document.getElementById('totalPages');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            
            const currentPage = parseInt(currentPageInput.value);
            const total = parseInt(totalPages.textContent);
            
            // Validate
            if (isNaN(currentPage) || currentPage < 0 || currentPage > total) {
                alert('Please enter a valid page number');
                return;
            }
            
            // Calculate percentage
            const percentage = Math.round((currentPage / total) * 100);
            
            // Update UI
            progressFill.style.width = `${percentage}%`;
            progressText.textContent = `${percentage}%`;
            
            // Send progress update to server
            updateBookProgress(bookId, currentPage);
        });
    }
    
    // Toggle owned status
    const ownedBtn = document.getElementById('ownedBtn');
    if (ownedBtn) {
        ownedBtn.addEventListener('click', () => {
            const bookId = ownedBtn.getAttribute('data-book-id');
            const isOwned = ownedBtn.classList.contains('active');
            
            // Toggle active state
            ownedBtn.classList.toggle('active');
            
            // Update owned status on server
            updateBookOwned(bookId, !isOwned);
        });
    }
    
    // Buy button
    const buyBtn = document.getElementById('buyBtn');
    if (buyBtn) {
        buyBtn.addEventListener('click', () => {
            alert("This would redirect to a purchase page in a real application");
        });
    }
    
  function updateBookStatus(bookId, status) {
    fetch('/ShelfControl/update-book', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }, 
        body: `action=status&book_id=${bookId}&status=${status}`
    })
    .then(response => response.text())
    .then(text => {
        console.log('Server response:', text);
        try {
            const data = JSON.parse(text);
            if (!data.success) {
                console.error('Error updating status:', data.message);
            }/* if (bookId.toString() !== data.book_id.toString()) {
                     window.location.href = data.redirect_url;
            }*/

        } catch (e) {
            console.error('Invalid JSON:', text);
        }
    });
}


    function updateBookProgress(bookId, pagesRead) {
        fetch('/ShelfControl/update-book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=progress&book_id=${bookId}&pages_read=${pagesRead}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating progress:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateBookOwned(bookId, isOwned) {
        fetch('/ShelfControl/update-book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=owned&book_id=${bookId}&is_owned=${isOwned ? 'Y' : 'N'}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating owned status:', data.message);
                // Revert UI change if server update failed
                ownedBtn.classList.toggle('active');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    fetch(`https://www.googleapis.com/books/v1/volumes?q=inauthor:${encodeURIComponent(author)}&maxResults=6`)
    .then(response => response.json())
    .then(data => {
        if (!data.items) {
            suggestionsGrid.innerHTML = '<p>No suggestions found.</p>';
            return;
        }

        // Filter books to exclude current title AND ensure they have cover images
        const books = data.items.filter(item =>
            item.volumeInfo.title.toLowerCase() !== currentTitle.toLowerCase() && 
            item.volumeInfo.imageLinks && 
            item.volumeInfo.imageLinks.thumbnail
        );

        if (books.length === 0) {
            suggestionsGrid.innerHTML = '<p>No suggestions with cover images found.</p>';
            return;
        }

        const seenBooks = new Set();
        //chestia asta da display la mai multe informatii despre carte, eu am pus doar sa arate pozele
        // books.forEach(book => {
        //     const title = book.volumeInfo.title || 'Unknown Title';
        //     const authors = book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : 'Unknown Author';
        //     const uniqueKey = `${title.toLowerCase()}|${authors.toLowerCase()}`;

        //     if (seenBooks.has(uniqueKey)) {
        //         return; // carte deja adăugată
        //     }
        //     seenBooks.add(uniqueKey);

        //     // Since we filtered earlier, we know thumbnail exists
        //     const thumbnail = book.volumeInfo.imageLinks.thumbnail;
        //     const link = `https://books.google.com/books?id=${book.id}`;

        //     const card = document.createElement('div');
        //     card.className = 'suggestion-card';
        //     card.innerHTML = `
        //         <a href="${link}" target="_blank">
        //             <img src="${thumbnail}" alt="${title}" class="suggestion-cover">
        //             <div class="suggestion-info">
        //                 <h3 class="suggestion-title">${title}</h3>
        //                 <p class="suggestion-author">${authors}</p>
        //             </div>
        //         </a>
        //     `;
        //     suggestionsGrid.appendChild(card);
        // });
        books.forEach(book => {
            const title = book.volumeInfo.title || 'Unknown Title';
            // Since we filtered earlier, we know thumbnail exists
            const thumbnail = book.volumeInfo.imageLinks.thumbnail;
            const link = `https://books.google.com/books?id=${book.id}`;

            const card = document.createElement('div');
            card.className = 'similar-book';
            card.innerHTML = `
                <a href="${link}" target="_blank">
                    <img src="${thumbnail}" alt="${title}" class="similar-book__img">
                </a>
            `;
            suggestionsGrid.appendChild(card);
        });
    });
    


document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');

    if (stars && ratingValue) {
        // Evidențiază stelele pe mouseover
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const value = parseInt(star.getAttribute('data-value'));
                highlightStars(value);
            });

            star.addEventListener('mouseout', () => {
                const currentRating = parseInt(ratingValue.value);
                highlightStars(currentRating);
            });

            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                ratingValue.value = value; // Setează valoarea rating-ului
                highlightStars(value); // Evidențiază stelele selectate
                console.log(`Rating selected: ${value}`);
            });
        });
    }

    // Funcție pentru evidențierea stelelor
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
                star.classList.remove('ri-star-line');
                star.classList.add('ri-star-fill');
            } else {
                star.classList.remove('active');
                star.classList.add('ri-star-line');
                star.classList.remove('ri-star-fill');
            }
        });
    }
});