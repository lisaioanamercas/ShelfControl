// ===================================== SEARCH =========================================
const searchButton = document.getElementById('search-button'),
      searchClose = document.getElementById('search-close'),
      searchContent = document.getElementById('search-content');  // Fixed variable name from 'saerchContent'

/* SEARCH show */
if(searchButton){
    searchButton.addEventListener('click', () =>{
        searchContent.classList.add('show-search')
    })
}

/* SEARCH hidden */
if(searchClose){
    searchClose.addEventListener('click', () =>{
        searchContent.classList.remove('show-search')
    })
}

// ===================================== PROFILE =========================================
const profileButton = document.getElementById('profile-button'),
      profileClose = document.getElementById('profile-close'),
      profileContent = document.getElementById('profile-content')

/* profile show */
if(profileButton){
    profileButton.addEventListener('click', () =>{
        profileContent.classList.add('show-profile')
    })
}

/* PROFILE hidden */
if(profileClose){
    profileClose.addEventListener('click', () =>{
        profileContent.classList.remove('show-profile')
    })
}
// ===================================== SHADOW HEADER =========================================
const shadowHeader = () =>{
    const header = document.getElementById('header')
    // Add a class if the bottom offset is greater than 50 of the viewport
    this.scrollY >= 50 ? header.classList.add('shadow-header') 
                       : header.classList.remove('shadow-header')
}
window.addEventListener('scroll', shadowHeader)  // Fixed function name from 'scrollHeader'


// ===================================== DARK THEME =========================================
// Select the theme button
const themeButton = document.getElementById('theme-button');
const body = document.body;

// Check if dark theme preference is stored in local storage
const getCurrentTheme = () => {
    const selectedTheme = localStorage.getItem('selected-theme');
    return selectedTheme || 'light'; // Default to light if no theme is stored
}

// Get the current theme icon (sun or moon)
const getCurrentIcon = () => {
    return themeButton.classList.contains('ri-sun-line') ? 'ri-sun-line' : 'ri-moon-line';
}

// Load theme from local storage when page loads
document.addEventListener('DOMContentLoaded', () => {
    const selectedTheme = getCurrentTheme();
    
    // Apply saved theme
    if (selectedTheme === 'dark') {
        body.classList.add('dark-theme');
        themeButton.classList.remove('ri-moon-line');
        themeButton.classList.add('ri-sun-line');
    } else {
        body.classList.remove('dark-theme');
        themeButton.classList.remove('ri-sun-line');
        themeButton.classList.add('ri-moon-line');
    }
});

// Toggle theme when the button is clicked
if (themeButton) {
    themeButton.addEventListener('click', () => {
        // Toggle dark theme class on body
        body.classList.toggle('dark-theme');
        
        // Toggle button icon (moon/sun)
        themeButton.classList.toggle('ri-moon-line');
        themeButton.classList.toggle('ri-sun-line');
        
        // Save theme preference to local storage
        const currentTheme = body.classList.contains('dark-theme') ? 'dark' : 'light';
        localStorage.setItem('selected-theme', currentTheme);
    });
}


// ===================================== Progress handling for read books =========================================
document.addEventListener('DOMContentLoaded', function() {
    // Set up click events for edit buttons
    const editButtons = document.querySelectorAll('.edit-progress-btn');
    
    editButtons.forEach((button, index) => {
        const editor = document.getElementById(`editor-${index}`);
        
        // Toggle editor when clicking pencil icon
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleEditor(editor);
        });
        
        // Set up save button
        const saveButton = editor.querySelector('.save-btn');
        saveButton.addEventListener('click', function() {
            const input = editor.querySelector('.page-input');
            const totalPages = editor.querySelector('.total-pages').textContent;
            updateBookProgress(index, input.value, totalPages);
            editor.classList.remove('active');
        });
        
        // Set up finish button
        const finishButton = editor.querySelector('.finish-btn');
        finishButton.addEventListener('click', function() {
            const totalPages = editor.querySelector('.total-pages').textContent;
            const bookTitle = document.querySelectorAll('.current-reads__book-title')[index].textContent;
            updateBookProgress(index, totalPages, totalPages);
            showFinishNotification(bookTitle);
            editor.classList.remove('active');
        });
    });
    
    // Close editors when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.edit-progress-btn') && 
            !e.target.closest('.progress-editor')) {
            document.querySelectorAll('.progress-editor').forEach(editor => {
                editor.classList.remove('active');
            });
        }
    });
});

function toggleEditor(editor) {
    // Close all other editors
    document.querySelectorAll('.progress-editor').forEach(ed => {
        if (ed !== editor) {
            ed.classList.remove('active');
        }
    });
    
    // Toggle current editor
    editor.classList.toggle('active');
}

function updateBookProgress(bookIndex, currentPage, totalPages) {
    const bookItem = document.querySelectorAll('.current-reads__item')[bookIndex];
    const progressBar = bookItem.querySelector('.progress-fill');
    const progressText = bookItem.querySelector('.progress-text');
    
    // Calculate percentage
    const percentage = Math.min(Math.round((currentPage / totalPages) * 100), 100);
    
    // Update UI
    progressBar.style.width = `${percentage}%`;
    progressText.textContent = `${percentage}%`;
}

function showFinishNotification(bookTitle) {
    const notification = document.createElement('div');
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = 'var(--first-color)';
    notification.style.color = 'white';
    notification.style.padding = '10px 20px';
    notification.style.borderRadius = '4px';
    notification.style.zIndex = '1000';
    notification.textContent = `"${bookTitle}" marked as finished!`;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}