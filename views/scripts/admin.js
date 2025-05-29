document.addEventListener('DOMContentLoaded', function () {
    console.log('Admin.js loaded'); // Debug line
    
    // Modal and form elements
    const adminButton = document.getElementById('admin-button');
    const adminModal = document.getElementById('add-book-modal');
    const modalClose = document.getElementById('modal-close');
    const cancelBtn = document.getElementById('cancel-btn');
    const bookForm = document.getElementById('book-form');
    const submitBtn = document.getElementById('submit-btn');
    const successMessage = document.getElementById('success-message');

    // Debug logging
    console.log('Admin button found:', !!adminButton);
    console.log('Admin modal found:', !!adminModal);

    // Cover upload
    const coverUploadArea = document.getElementById('cover-upload-area');
    const coverUrlInput = document.getElementById('cover-url');
    const coverPreview = document.getElementById('cover-preview');
    const coverImage = document.getElementById('cover-image');
    const removeCoverBtn = document.getElementById('remove-cover-btn');

// Preview cover when URL is entered
    if (coverUrlInput) {
        coverUrlInput.addEventListener('blur', function() {
            const url = this.value.trim();
            if (url) {
                previewCoverUrl(url);
            }
        });
        
        // Also preview when pressing Enter
        coverUrlInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const url = this.value.trim();
                if (url) {
                    previewCoverUrl(url);
                }
            }
        });
    }
    
    // Function to preview the cover using our proxy
    // Function to preview the cover - modified to quietly handle errors
    function previewCoverUrl(url) {
        // Only proceed if we have a URL
        if (!url) return;
        
        // Set image source directly without proxy
        coverImage.src = url;
        
        // Show preview when loaded
        coverImage.onload = function() {
            coverPreview.style.display = 'flex';
        };
        
        // Silently handle load errors - just don't show preview
        coverImage.onerror = function() {
            // Still show preview with placeholder or fallback image
            coverImage.src = '/ShelfControl/assets/images/cover-placeholder.jpg'; // Use a placeholder
            coverPreview.style.display = 'flex';
        };
    }

    
    // Remove button handler
    if (removeCoverBtn) {
        removeCoverBtn.addEventListener('click', function() {
            coverUrlInput.value = '';
            coverPreview.style.display = 'none';
        });
    }

    // Ensure we don't try to preview on page load if no URL
    // This prevents the initial error
    if (coverUrlInput && coverUrlInput.value) {
        previewCoverUrl(coverUrlInput.value);
    }
    
    let selectedFile = null;

    // ====================== EARLY EXIT IF ADMIN BUTTON NOT PRESENT ======================
    if (!adminButton) {
        console.log('No admin button found - user likely not admin');
        return;
    }

    // ====================== HANDLE MISSING MODAL (REDIRECT) ======================
    if (!adminModal) {
        console.log('Admin button found but modal missing - setting up redirect');
        adminButton.addEventListener('click', () => {
            console.log('Admin button clicked - redirecting');
            window.location.href = '/ShelfControl/home?openAdminModal=true';
        });
        return;
    }

    console.log('Both admin button and modal found - setting up modal functionality');

    if (adminModal) {
        // Enable transitions after a small delay to prevent flash
        setTimeout(() => {
            adminModal.classList.add('transitions-enabled');
        }, 50);
    }
    // ====================== MODAL LOGIC ======================
    function openModal() {
        console.log('Opening modal');
        if (document.body.classList.contains('dark-theme')) {
            adminModal.classList.add('dark-theme');
        } else {
            adminModal.classList.remove('dark-theme');
        }

        adminModal.classList.add('show-modal');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        console.log('Closing modal');
        adminModal.classList.remove('show-modal');
        document.body.style.overflow = 'auto';

        if (bookForm) {
            bookForm.reset();
        }

        resetCoverPreview();
    }

    // Open modal button
    adminButton.addEventListener('click', (e) => {
        console.log('Admin button clicked');
        e.preventDefault();
        openModal();
    });

    // Close modal events
    modalClose?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    adminModal.addEventListener('click', (e) => {
        if (e.target === adminModal) closeModal();
    });

    // ====================== AUTO OPEN MODAL FROM URL PARAM ======================
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('openAdminModal') === 'true') {
        console.log('Opening modal from URL parameter');
        openModal();
        history.replaceState({}, document.title, window.location.pathname); // clean URL
    }

    // ====================== COVER UPLOAD ======================
    if (coverUploadArea && coverInput) {
        coverUploadArea.addEventListener('click', () => coverInput.click());

        coverInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) handleFile(file);
        });

        coverUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            coverUploadArea.classList.add('dragover');
        });

        coverUploadArea.addEventListener('dragleave', () => {
            coverUploadArea.classList.remove('dragover');
        });

        coverUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            coverUploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) handleFile(files[0]);
        });
    }

    function handleFile(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }

        selectedFile = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            coverImage.src = e.target.result;
            coverPreview.style.display = 'flex';
            coverUploadArea.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    removeCoverBtn?.addEventListener('click', resetCoverPreview);

    function resetCoverPreview() {
        if (!coverImage || !coverPreview || !coverUrlInput) return;
        coverUrlInput.value = '';
        coverImage.src = '';
        coverPreview.style.display = 'none';
    }

    // ====================== FORM SUBMISSION ======================
    if (bookForm) {
        bookForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            console.log('Form submitted');

            submitBtn?.classList.add('loading');
            submitBtn.disabled = true;

            try {
                const formData = new FormData(bookForm);

                const response = await fetch('/ShelfControl/admin/add-book', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    showSuccessMessage();
                    closeModal();
                } else {
                    alert(data.message || 'Failed to add book');
                }
            } catch (error) {
                console.error('Error adding book:', error);
                alert('Error adding book. Please try again.');
            } finally {
                submitBtn?.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });
    }

    function showSuccessMessage() {
        successMessage?.classList.add('show');
        setTimeout(() => {
            successMessage?.classList.remove('show');
        }, 3000);
    }

    // Prevent Enter key from submitting the form prematurely
    document.querySelectorAll('.form-input').forEach((input) => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });
    });
});