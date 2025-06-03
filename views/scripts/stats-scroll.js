function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (!section) return;
    
    // Get the fixed header height to adjust scroll position
    const header = document.querySelector('header');
    const headerHeight = header ? header.offsetHeight : 0;
    
    // Calculate the correct scroll position
    const sectionTop = section.getBoundingClientRect().top + window.pageYOffset;
    const scrollPosition = sectionTop - headerHeight - 20; // Additional 20px padding
    
    // Scroll smoothly
    window.scrollTo({
        top: scrollPosition,
        behavior: 'smooth'
    });
    
    // Highlight the section briefly
    section.classList.add('highlight-section');
    setTimeout(() => {
        section.classList.remove('highlight-section');
    }, 2000);
}