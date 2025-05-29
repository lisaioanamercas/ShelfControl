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
window.addEventListener('scroll', shadowHeader)