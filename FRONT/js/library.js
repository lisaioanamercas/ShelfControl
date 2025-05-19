// ===================================== LIBRARY PAGE JS =========================================
document.addEventListener('DOMContentLoaded', function() {
    // Filter buttons functionality
    const filterBtns = document.querySelectorAll('.library-filter__btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you would add filtering functionality
            const filter = this.textContent.toLowerCase();
            console.log(`Filtering by: ${filter}`);
            
            // Example implementation would filter the books based on categories
            // For now, we're just logging the filter value
        });
    });
    
    // Book action buttons functionality
    const bookmarkBtns = document.querySelectorAll('.book-card__btn');
    
    bookmarkBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Toggle active state
            this.classList.toggle('book-card__btn--active');
            
            // If it's a bookmark button
            if (this.querySelector('.ri-bookmark-line')) {
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('ri-bookmark-line')) {
                    icon.classList.remove('ri-bookmark-line');
                    icon.classList.add('ri-bookmark-fill');
                } else {
                    icon.classList.remove('ri-bookmark-fill');
                    icon.classList.add('ri-bookmark-line');
                }
            }
            
            // If it's an info button
            if (this.querySelector('.ri-information-line')) {
                // Here you could show a modal with book details
                console.log('Show book details');
            }
        });
    });
    
    // Theme toggle functionality (if not already implemented in main.js)
    const themeButton = document.getElementById('theme-button');
    
    if (themeButton) {
        themeButton.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            
            // Change theme icon
            if (themeButton.classList.contains('ri-moon-line')) {
                themeButton.classList.remove('ri-moon-line');
                themeButton.classList.add('ri-sun-line');
            } else {
                themeButton.classList.remove('ri-sun-line');
                themeButton.classList.add('ri-moon-line');
            }
        });
    }
});

// ===================================== ACTIVE NAV LINK =========================================
// Update the active navigation link
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.pageYOffset;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 50,
              sectionId = current.getAttribute('id');

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            document.querySelector('.nav__link[href*=' + sectionId + ']').classList.add('active-link');
        } else {
            document.querySelector('.nav__link[href*=' + sectionId + ']').classList.remove('active-link');
        }
    });
}

window.addEventListener('scroll', scrollActive);