// Dark Theme Manager
// This file handles dark theme functionality across all !!! pages

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