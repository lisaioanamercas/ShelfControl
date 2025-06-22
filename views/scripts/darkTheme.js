
const themeButton = document.getElementById('theme-button');
const body = document.body;

const getCurrentTheme = () => {
    const selectedTheme = localStorage.getItem('selected-theme');
    return selectedTheme || 'light'; // Default to light if no theme is stored
}

const getCurrentIcon = () => {
    return themeButton.classList.contains('ri-sun-line') ? 'ri-sun-line' : 'ri-moon-line';
}

document.addEventListener('DOMContentLoaded', () => {
    const selectedTheme = getCurrentTheme();
    
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


if (themeButton) {
    themeButton.addEventListener('click', () => {
        body.classList.toggle('dark-theme');
        
        themeButton.classList.toggle('ri-moon-line');
        themeButton.classList.toggle('ri-sun-line');
        
        const currentTheme = body.classList.contains('dark-theme') ? 'dark' : 'light';
        localStorage.setItem('selected-theme', currentTheme);
    });
}