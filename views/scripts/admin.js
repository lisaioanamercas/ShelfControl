document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const adminButton = document.getElementById('admin-button');
    const adminModal = document.getElementById('add-book-modal');
    const modalClose = document.getElementById('modal-close');
    const cancelBtn = document.getElementById('cancel-btn');
    
    // Form elements
    const bookForm = document.getElementById('book-form');
    const submitBtn = document.getElementById('submit-btn');
    const successMessage = document.getElementById('success-message');
    
    // Cover upload elements
    const coverUploadArea = document.getElementById('cover-upload-area');
    const coverInput = document.getElementById('cover-input');
    const coverPreview = document.getElementById('cover-preview');
    const coverImage = document.getElementById('cover-image');
    const removeCoverBtn = document.getElementById('remove-cover-btn');
    
    let selectedFile = null;

    // ===================================== MODAL FUNCTIONALITY =========================================
    
    function openModal() {
        adminModal.classList.add('show-modal');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        adminModal.classList.remove('show-modal');
        document.body.style.overflow = 'auto';
        
        // Reset form
        if (bookForm) {
            bookForm.reset();
        }
        
        // Reset cover preview
        resetCoverPreview();
    }

    // Open modal when admin button is clicked
    if (adminButton) {
        adminButton.addEventListener('click', openModal);
    }
    
    // Close modal events
    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    
    // Close modal when clicking outside
    if (adminModal) {
        adminModal.addEventListener('click', function(e) {
            if (e.target === adminModal) {
                closeModal();
            }
        });
    }

    // ===================================== COVER UPLOAD =========================================
    
    // Upload area click - open file dialog
    if (coverUploadArea && coverInput) {
        coverUploadArea.addEventListener('click', () => {
            coverInput.click();
        });
        
        // File input change
        coverInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                handleFile(file);
            }
        });
        
        // Drag and drop functionality
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
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });
    }
    
    // Handle file selection/validation
    function handleFile(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }

        selectedFile = file;
        
        // Show preview
        if (coverImage && coverPreview && coverUploadArea) {
            const reader = new FileReader();
            reader.onload = (e) => {
                coverImage.src = e.target.result;
                coverPreview.style.display = 'flex';
                coverUploadArea.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Remove cover functionality
    if (removeCoverBtn) {
        removeCoverBtn.addEventListener('click', resetCoverPreview);
    }
    
    function resetCoverPreview() {
        if (!coverImage || !coverPreview || !coverUploadArea || !coverInput) return;
        
        selectedFile = null;
        coverImage.src = '';
        coverPreview.style.display = 'none';
        coverUploadArea.style.display = 'block';
        coverInput.value = '';
    }

    // ===================================== FORM SUBMISSION =========================================
    
    // Form submission handler
    if (bookForm) {
        bookForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            }

            try {
                // Gather form data
                const formData = new FormData(bookForm);

                // If you want to handle cover upload, you need to upload it separately first and get the file path

                // Send form data to backend
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
                if (submitBtn) {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }
            }
        });
    }
    
    function showSuccessMessage() {
        successMessage.classList.add('show');
        setTimeout(() => {
            successMessage.classList.remove('show');
        }, 3000);
    }

    // Prevent form submission on Enter key in input fields
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    });
});
