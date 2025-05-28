// // ===================================== SEARCH =========================================
// const searchButton = document.getElementById('search-button'),
//       searchClose = document.getElementById('search-close'),
//       searchContent = document.getElementById('search-content');  // Fixed variable name from 'saerchContent'

// /* SEARCH show */
// if(searchButton){
//     searchButton.addEventListener('click', () =>{
//         searchContent.classList.add('show-search')
//     })
// }

// /* SEARCH hidden */
// if(searchClose){
//     searchClose.addEventListener('click', () =>{
//         searchContent.classList.remove('show-search')
//     })
// }

// // ===================================== PROFILE =========================================
// const profileButton = document.getElementById('profile-button'),
//       profileClose = document.getElementById('profile-close'),
//       profileContent = document.getElementById('profile-content')

// /* profile show */
// if(profileButton){
//     profileButton.addEventListener('click', () =>{
//         profileContent.classList.add('show-profile')
//     })
// }

// /* PROFILE hidden */
// if(profileClose){
//     profileClose.addEventListener('click', () =>{
//         profileContent.classList.remove('show-profile')
//     })
// }
// // ===================================== SHADOW HEADER =========================================
// const shadowHeader = () =>{
//     const header = document.getElementById('header')
//     // Add a class if the bottom offset is greater than 50 of the viewport
//     this.scrollY >= 50 ? header.classList.add('shadow-header') 
//                        : header.classList.remove('shadow-header')
// }
// window.addEventListener('scroll', shadowHeader)  // Fixed function name from 'scrollHeader'



// // ===================================== ADMIN DROPDOWN =========================================
// // Admin modal functionality
// const adminButton = document.getElementById('admin-button');
// const adminModal = document.getElementById('add-book-modal');
// const modalClose = document.getElementById('modal-close');

// // Open modal when admin button is clicked
// if (adminButton && adminModal) {
//     adminButton.addEventListener('click', () => {
//         adminModal.classList.add('show-modal');
//         document.body.style.overflow = 'hidden';
//     });
    
//     // Close modal when X is clicked
//     if (modalClose) {
//         modalClose.addEventListener('click', () => {
//             adminModal.classList.remove('show-modal');
//             document.body.style.overflow = 'auto';
//         });
//     }
    
//     // Close modal when clicking outside
//     adminModal.addEventListener('click', (e) => {
//         if (e.target === adminModal) {
//             adminModal.classList.remove('show-modal');
//             document.body.style.overflow = 'auto';
//         }
//     });
// }
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

// ===================================== ADMIN BUTTON =========================================
// document.addEventListener('DOMContentLoaded', function() {
//     const adminButton = document.getElementById('admin-button');
//     const adminModal = document.getElementById('add-book-modal');
//     const modalClose = document.getElementById('modal-close');
    
//     if(adminButton && adminModal) {
//         console.log("Admin button and modal found!");
        
//         // Open modal when admin button is clicked
//         adminButton.addEventListener('click', function() {
//             console.log("Admin button clicked!");
//             adminModal.classList.add('show-modal');
//             document.body.style.overflow = 'hidden';
//         });
        
//         // Close modal when the X button is clicked
//         if(modalClose) {
//             modalClose.addEventListener('click', function() {
//                 adminModal.classList.remove('show-modal');
//                 document.body.style.overflow = 'auto';
//             });
//         }
        
//         // Close modal when clicking outside
//         adminModal.addEventListener('click', function(e) {
//             if (e.target === adminModal) {
//                 adminModal.classList.remove('show-modal');
//                 document.body.style.overflow = 'auto';
//             }
//         });
//     } else {
//         console.log("Admin button or modal not found:", {
//             adminButton: !!adminButton,
//             adminModal: !!adminModal
//         });
//     }
// });

// Add this at the end of your header.js file
document.addEventListener('DOMContentLoaded', function() {
    // Admin modal functionality
    const adminButton = document.getElementById('admin-button');
    const adminModal = document.getElementById('add-book-modal');
    const modalClose = document.getElementById('modal-close');
    const cancelBtn = document.getElementById('cancel-btn');
    
    // Open modal when admin button is clicked
    if (adminButton && adminModal) {
        adminButton.addEventListener('click', function() {
            adminModal.classList.add('show-modal');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
        
        // Close modal when X button is clicked
        if (modalClose) {
            modalClose.addEventListener('click', function() {
                adminModal.classList.remove('show-modal');
                document.body.style.overflow = 'auto'; // Enable scrolling
            });
        }
        
        // Close modal when Cancel button is clicked
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                adminModal.classList.remove('show-modal');
                document.body.style.overflow = 'auto'; // Enable scrolling
            });
        }
        
        // Close modal when clicking outside
        adminModal.addEventListener('click', function(e) {
            if (e.target === adminModal) {
                adminModal.classList.remove('show-modal');
                document.body.style.overflow = 'auto'; // Enable scrolling
            }
        });
    }
});