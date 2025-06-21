document.addEventListener('DOMContentLoaded', () => {
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
     const urlParams = new URLSearchParams(window.location.search);
     const bookId = urlParams.get('id');

 
     if (bookId) {
        fetchReviews(bookId);
         setInterval(() => {
        fetchReviews(bookId);
    }, 60000);
    }
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

    
      document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-review-btn');
    if (btn) {
        const reviewId = btn.getAttribute('data-review-id');
        console.log('Review ID:', reviewId); // DEBUG
        if (reviewId && confirm('Sigur vrei să ștergi acest review?')) {
            fetch('/ShelfControl/delete-review', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `review_id=${encodeURIComponent(reviewId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Recenzia a fost ștearsă cu succes!');
                    btn.closest('.review-item').remove();
                } else {
                    alert(data.message || 'A apărut o eroare la ștergerea recenziei.');
                }
            })
            .catch(error => {
                alert('Eroare la conexiune!');
            });
        }
    }
});
    
     
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
                                window.location.reload();
                            } else {
                                alert(data.message || 'A apărut o eroare!');
                            }
                        })

            e.preventDefault();
            console.log('Review submitted');});
    }
     
    const author = authorElement.getAttribute('data-author-name');
    console.log('Autor:', author);
        
    const currentTitle = titleElement.innerText;
    console.log('Titlu:', currentTitle);

    const bookSummary = document.getElementById('bookSummary');
    const readMoreBtn = document.getElementById('readMoreBtn');
    
    if (bookSummary && readMoreBtn) {
        const shortDescP = bookSummary.querySelector('p:first-child');
        const fullDescP = bookSummary.querySelector('.full-description');
        
        if (shortDescP && fullDescP) {
            readMoreBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                shortDescP.innerHTML = fullDescP.innerHTML;
                
                readMoreBtn.style.display = 'none';
            });
        }
    }
    
    if (statusBtn) {
        statusBtn.addEventListener('click', () => {
            statusOptions.classList.toggle('active');
        });
    }
    
    document.addEventListener('click', (e) => {
        if (statusBtn && !statusBtn.contains(e.target) && !statusOptions.contains(e.target)) {
            statusOptions.classList.remove('active');
        }
    });
    
   function fetchReviews(bookId) {
    fetch(`/ShelfControl/reviews?book_id=${encodeURIComponent(bookId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.reviews)) {
                console.log('Recenzii primite:', data.reviews);
                renderReviews(data.reviews||[], data.userReviews || []);
            } else {
                renderReviews([]);
            }
        })
        .catch(() => {
            console.error('Eroare la încărcarea recenziilor.');
            renderReviews([]);
        });
}
function renderStars(stars) {
    let html = '';
    for (let i = 0; i < 5; i++) {
        html += i < stars ? '★' : '☆';
    }
    return html;
}

          
function renderReviews(reviews, userReviews) {
    const reviewsList = document.getElementById('reviewsList');

    if (!reviewsList) return;

    let html = '';

    if (userReviews && userReviews.length > 0) {
        html += userReviews.map(userReview => `
            <div class="review-item user-review">
                <div class="review-header">
                    <span class="reviewer-name">${userReview.USERNAME}</span>
                    <span class="review-rating">${renderStars(parseInt(userReview.STARS))}</span>
                    <button class="delete-review-btn" data-review-id="${userReview.REVIEW_ID}"> 
                     <i class="ri-delete-bin-line">
                     </i></button>
                </div>
                <div class="review-content">
                     ${userReview.TEXT ? userReview.TEXT : ' '}
                </div>
            </div>
        `).join('');
    }

    const otherReviews = reviews?.filter(r => {
        return !userReviews?.some(ur => ur.REVIEW_ID === r.REVIEW_ID);
    });

    if (otherReviews && otherReviews.length > 0) {
        html += otherReviews.map(review => `
            <div class="review-item">
                <div class="review-header">
                    <span class="reviewer-name">${review.USERNAME || 'Anonim'}</span>
                    <span class="review-rating">${renderStars(parseInt(review.STARS))}</span>
                </div>
                <div class="review-content">
                     ${review.TEXT ? review.TEXT : ' '}
                </div>
            </div>
        `).join('');
    }

    if (html === '') {
        html = '<p>Nu există recenzii pentru această carte.</p>';
    }

    reviewsList.innerHTML = html;
}

    const statusOptionElements = document.querySelectorAll('.status-option');
    statusOptionElements.forEach(option => {
        option.addEventListener('click', () => {
            const selectedStatus = option.getAttribute('data-status');
            
            
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
            
            currentStatus.textContent = selectedStatus;
            statusOptions.classList.remove('active');
            
            if (selectedStatus === 'reading') {
                readingProgress.classList.add('active');
            } else {
                readingProgress.classList.remove('active');
            }
            
            updateBookStatus(bookId, selectedStatus);
        });
    });
    
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    if (saveProgressBtn) {
        saveProgressBtn.addEventListener('click', () => {
            const currentPageInput = document.getElementById('currentPageInput');
            const totalPages = document.getElementById('totalPages');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            
            const currentPage = parseInt(currentPageInput.value);
            const total = parseInt(totalPages.textContent);
            
            if (isNaN(currentPage) || currentPage < 0 || currentPage > total) {
                alert('Please enter a valid page number');
                return;
            }
            
            const percentage = Math.min(Math.round((currentPage / total) * 100), 100);
            
            progressFill.style.width = `${percentage}%`;
            progressText.textContent = `${percentage}%`;
            
      
            updateBookProgress(bookId, currentPage);
            
         
            if (currentPage >= total) {
                setTimeout(() => {
                
                    window.location.reload();
                }, 60);
            }
        });
    }
    

    const ownedBtn = document.getElementById('ownedBtn');
    if (ownedBtn) {
        ownedBtn.addEventListener('click', () => {
            const isOwned = ownedBtn.classList.contains('active');
            
        
            ownedBtn.classList.toggle('active');
            
      
            updateBookOwned(bookId, !isOwned);
        });
    }

const groupReadingOption = document.querySelector('.group-reading');
if (groupReadingOption) {
    groupReadingOption.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent parent event handlers from executing
        
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
    

    const closeBtn = modalOverlay.querySelector('.modal-close');
    closeBtn.addEventListener('click', () => {
        modalOverlay.remove();
    });
    
  
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.remove();
        }
    });
    

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
                
              
                if (currentStatus) {
                    currentStatus.textContent = 'reading';
                }
                if (statusOptions) {
                    statusOptions.classList.remove('active');
                }
                if (readingProgress) {
                    readingProgress.classList.add('active');
                }
                
             
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
                }

            } catch (e) {
                console.error('Invalid JSON:', text);
            }
        });
    }


    function updateBookProgress(bookId, pagesRead) {
     
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
            
                if (parseInt(pagesRead) >= totalPages) {
                  
                    const currentStatus = document.getElementById('currentStatus');
                    if (currentStatus) {
                        currentStatus.textContent = 'completed';
                    }
                    
                    const readingProgress = document.getElementById('readingProgress');
                    if (readingProgress) {
                        readingProgress.classList.remove('active');
                    }
                    
                    showCompletionMessage();
                }
            } else {
                console.error('Error updating progress:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

  
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
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
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

        const books = data.items.filter(item =>
            item.volumeInfo.title.toLowerCase() !== currentTitle.toLowerCase() && 
            item.volumeInfo.imageLinks && 
            item.volumeInfo.imageLinks.thumbnail
        );

        if (books.length === 0) {
            suggestionsGrid.innerHTML = '<p>No suggestions with cover images found.</p>';
            return;
        }

   
       
        books.forEach(book => {
            const title = book.volumeInfo.title || 'Unknown Title';
            const thumbnail = book.volumeInfo.imageLinks.thumbnail;
        

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
                ratingValue.value = value; 
                highlightStars(value); 
                console.log(`Rating selected: ${value}`);
            });
        });
    }

   
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