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

     const buyBtn = document.getElementById('buyBtn');

    const libraryPopupOverlay = document.getElementById('libraryPopupOverlay');
    const closeLibraryPopup = document.getElementById('closeLibraryPopup');
    const libraryList = document.getElementById('libraryList');


    if (buyBtn && libraryPopupOverlay) {
        buyBtn.addEventListener('click', () => {
            libraryPopupOverlay.style.display = 'flex';
        });
    }
    if (closeLibraryPopup && libraryPopupOverlay) {
        closeLibraryPopup.addEventListener('click', () => {
            libraryPopupOverlay.style.display = 'none';
        });
    }
    if (libraryPopupOverlay) {
        libraryPopupOverlay.addEventListener('click', (e) => {
            if (e.target === libraryPopupOverlay) {
                libraryPopupOverlay.style.display = 'none';
            }
        });
    }
    if (libraryList) {

        libraryList.innerHTML = '<li>Se încarcă bibliotecile...</li>';
        console.log('Incepe fetch-ul pentru biblioteci');

        fetch('/ShelfControl/api/libraries')
        .then(res => res.json())
        .then(libraries => {
            console.log(libraries);
            libraryList.innerHTML = '';
            if (libraries.length === 0) {
                libraryList.innerHTML = '<li>Nu s-au găsit biblioteci.</li>';
            } else {
                console.log("Biblioteci găsite:", libraries);
                libraries.forEach(lib => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div class="library-card">
                            <h4>${lib.name}</h4>
                            <p>${lib.address}</p>
                        </div>
                    `;
                    libraryList.appendChild(li);
                });
            }
        })
        .catch((error) => {
            console.error('Eroare la încărcarea bibliotecilor:', error);
            libraryList.innerHTML = '<li>Eroare la încărcarea bibliotecilor.</li>';
        });
    }


     
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
                const urlParams = new URLSearchParams(window.location.search);
                bookId = urlParams.get('id');
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
     
    // const author = authorElement.innerText;
    // console.log('Autor:', author);
    const author = authorElement.getAttribute('data-author-name');
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
    
    // Update reading progress - modificat mai pont
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
            const percentage = Math.min(Math.round((currentPage / total) * 100), 100);
            
            // Update UI
            progressFill.style.width = `${percentage}%`;
            progressText.textContent = `${percentage}%`;
            
            // Send progress update to server
            updateBookProgress(bookId, currentPage);
            
            // If user has completed the book, maybe reload after a delay 
            if (currentPage >= total) {
                setTimeout(() => {
                    // Optional: reload the page after showing notification
                    window.location.reload();
                }, 60);
            }
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

    // Group reading option in the status dropdown
const groupReadingOption = document.querySelector('.group-reading');
if (groupReadingOption) {
    groupReadingOption.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent parent event handlers from executing
        
        // Get the book ID
      /*  const bookId = document.querySelector('.owned-btn').getAttribute('data-book-id');
        if (!bookId) {
            console.error('Could not determine book ID');
            return;
        }*/
        const urlParams = new URLSearchParams(window.location.search);
        bookId = urlParams.get('id');
        // Fetch user's groups
        fetch('/ShelfControl/user-groups')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.groups.length === 0) {
                        alert("You don't belong to any reading groups. Create or join a group first.");
                    } else {
                        console.log('Groups:', data.groups, 'BookId:', bookId);
                        showGroupSelectionPopup(data.groups, bookId);
                    }
                } else {
                    alert(`Error: ${data.error}`);
                }
            })
            .catch(error => {
                console.error('Error fetching groups:', error);
                alert('Failed to load your reading groups.');
            });
    });
}

function showGroupSelectionPopup(groups, bookId) {
    console.log('Popup function called', groups, bookId);
    // Create a modal popup
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'modal-overlay';
    modalOverlay.style.display = 'flex';
    
    const modalHtml = `
        <div class="modal-container">
            <div class="modal-header">
                <h2>Select a Reading Group</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Select a group to start reading this book with:</p>
                <div class="group-select-list">
                    ${groups.map(group => `
                        <div class="group-select-item" data-group-id="${group.GROUP_ID}">
                            <div class="group-select-info">
                                <span class="group-select-name">${group.GROUP_NAME}</span>
                                <span class="group-select-meta">${group.MEMBER_COUNT} members</span>
                            </div>
                            <button class="select-group-btn">Select</button>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    `;
    
    modalOverlay.innerHTML = modalHtml;
    document.body.appendChild(modalOverlay);
    
    // Close button functionality
    const closeBtn = modalOverlay.querySelector('.modal-close');
    closeBtn.addEventListener('click', () => {
        modalOverlay.remove();
    });
    
    // Close on outside click
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.remove();
        }
    });
    
    // Group selection
    const selectButtons = modalOverlay.querySelectorAll('.select-group-btn');
    selectButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const groupItem = e.target.closest('.group-select-item');
            const groupId = groupItem.getAttribute('data-group-id');
            startGroupReading(groupId, bookId, modalOverlay);
        });
    });
}

    function startGroupReading(groupId, bookId, modalOverlay) {
        fetch('/ShelfControl/start-group-reading', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `group_id=${groupId}&book_id=${bookId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('The book has been assigned to all members of the group!');
                modalOverlay.remove();
                
                // Update UI to show reading status
                if (currentStatus) {
                    currentStatus.textContent = 'reading';
                }
                if (statusOptions) {
                    statusOptions.classList.remove('active');
                }
                if (readingProgress) {
                    readingProgress.classList.add('active');
                }
                
                // Reload page to update UI
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while starting group reading.');
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
        // Get the total pages
        const totalPagesElem = document.getElementById('totalPages');
        const totalPages = parseInt(totalPagesElem.textContent);

        fetch('/ShelfControl/update-book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=progress&book_id=${bookId}&pages_read=${pagesRead}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Check if book is now completed (pages read equals total pages)
                if (parseInt(pagesRead) >= totalPages) {
                    // Update the status display
                    const currentStatus = document.getElementById('currentStatus');
                    if (currentStatus) {
                        currentStatus.textContent = 'completed';
                    }
                    
                    // Hide the reading progress section
                    const readingProgress = document.getElementById('readingProgress');
                    if (readingProgress) {
                        readingProgress.classList.remove('active');
                    }
                    
                    // Show completion message
                    showCompletionMessage();
                }
            } else {
                console.error('Error updating progress:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Add this new function to show a completion message
    function showCompletionMessage() {
        const notification = document.createElement('div');
        notification.className = 'completion-notification';
        notification.innerHTML = `
            <div class="completion-content">
                <i class="ri-check-double-line"></i>
                <p>Congratulations! You've completed this book!</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Add animation class after a short delay
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Remove after a few seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 500);
        }, 3000);
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
            <a href="/ShelfControl/book-details?id=${book.id}" target="_blank">
                <img src="${thumbnail}" alt="${title}" class="similar-book__img">
            </a>
        `;
            suggestionsGrid.appendChild(card);
        });
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