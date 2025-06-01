// ===================================== SEARCH =========================================
const searchButton = document.getElementById('search-button'),
      searchClose = document.getElementById('search-close'),
      searchContent = document.getElementById('search-content');

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
// ===================================== PROFILE =========================================
const profileButton = document.getElementById('profile-button'),
      profileClose = document.getElementById('profile-close'),
      profileContent = document.getElementById('profile-content');

/* profile show */
if(profileButton){
    profileButton.addEventListener('click', (e) =>{
        e.stopPropagation(); // Prevent document click from immediately closing it
        profileContent.classList.add('show-profile');
    })
}

/* PROFILE hidden */
if(profileClose){
    profileClose.addEventListener('click', () =>{
        profileContent.classList.remove('show-profile');
    })
}

// Close profile when clicking outside
document.addEventListener('click', (e) => {
    // Check if profile is open and click is outside profile and profile button
    if (
        profileContent && 
        profileContent.classList.contains('show-profile') && 
        !profileContent.contains(e.target) &&
        !profileButton.contains(e.target)
    ) {
        profileContent.classList.remove('show-profile');
    }
});

console.log("Profile elements:", {
    button: profileButton,
    content: profileContent,
    close: profileClose
});


// ===================================== SHADOW HEADER =========================================
const shadowHeader = () =>{
    const header = document.getElementById('header')
    // Add a class if the bottom offset is greater than 50 of the viewport
    this.scrollY >= 50 ? header.classList.add('shadow-header') 
                       : header.classList.remove('shadow-header')
}
window.addEventListener('scroll', shadowHeader)