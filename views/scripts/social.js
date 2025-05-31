document.addEventListener('DOMContentLoaded', function() {
    // Create Group Modal
    const createGroupBtn = document.getElementById('createGroupBtn');
    const createGroupModal = document.getElementById('createGroupModal');
    const closeCreateGroupModal = document.getElementById('closeCreateGroupModal');
    const createGroupForm = document.getElementById('createGroupForm');
    
    // Add Member Modal
    const addMemberBtns = document.querySelectorAll('.add-member-btn');
    const addMemberModal = document.getElementById('addMemberModal');
    const closeAddMemberModal = document.getElementById('closeAddMemberModal');
    const searchUserBtn = document.getElementById('searchUserBtn');
    const userSearchInput = document.getElementById('userSearchInput');
    const searchResults = document.getElementById('searchResults');
    
    let currentGroupId = null;
    
    // Create Group Modal functionality
    if (createGroupBtn) {
        createGroupBtn.addEventListener('click', () => {
            createGroupModal.style.display = 'flex';
        });
    }
    
    if (closeCreateGroupModal) {
        closeCreateGroupModal.addEventListener('click', () => {
            createGroupModal.style.display = 'none';
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === createGroupModal) {
            createGroupModal.style.display = 'none';
        }
        if (e.target === addMemberModal) {
            addMemberModal.style.display = 'none';
            searchResults.innerHTML = '';
            userSearchInput.value = '';
        }
    });
    
    // Create Group Form Submission
    if (createGroupForm) {
        createGroupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(createGroupForm);
            
            try {
                const response = await fetch('/ShelfControl/create-group', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Group created successfully!');
                    window.location.reload();
                } else {
                    alert(`Error: ${data.message || 'Failed to create group'}`);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while creating the group.');
            }
        });
    }
    
    // Add Member Modal functionality
    if (addMemberBtns.length > 0) {
        addMemberBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                currentGroupId = btn.getAttribute('data-group-id');
                addMemberModal.style.display = 'flex';
            });
        });
    }
    
    if (closeAddMemberModal) {
        closeAddMemberModal.addEventListener('click', () => {
            addMemberModal.style.display = 'none';
            searchResults.innerHTML = '';
            userSearchInput.value = '';
        });
    }
    
    // Search Users - updated with better error handling
    if (searchUserBtn && userSearchInput) {
        searchUserBtn.addEventListener('click', searchUsers);
        
        // Add enter key support
        userSearchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchUsers();
            }
        });
    }
    
    // Search users function
    async function searchUsers() {
        const email = userSearchInput.value.trim();
        
        if (!email) {
            alert('Please enter an email to search.');
            return;
        }
        
        // Show loading indicator
        searchResults.innerHTML = '<p>Searching...</p>';
        
        try {
            // FIXED: Changed URL to match the route in index.php
            const url = `/ShelfControl/search-users?email=${encodeURIComponent(email)}`;
            console.log('Searching users with URL:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Get response text first to debug
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            // Try to parse as JSON
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Invalid JSON response from server');
            }
            
            displaySearchResults(data);
        } catch (error) {
            console.error('Search error:', error);
            searchResults.innerHTML = `<p class="error-message">Error searching: ${error.message}</p>`;
        }
    }
    
    // Display search results
    function displaySearchResults(data) {
        if (!data.success) {
            searchResults.innerHTML = `<p class="error-message">${data.error || 'Unknown error'}</p>`;
            return;
        }
        
        const users = data.users;
        
        if (users.length === 0) {
            searchResults.innerHTML = '<p>No users found matching that email.</p>';
            return;
        }
        
        let html = '<div class="user-results">';
        users.forEach(user => {
            html += `
                <div class="user-item">
                    <div class="user-info">
                        <span class="user-name">${escapeHtml(user.USERNAME || user.username || '')}</span>
                        <span class="user-email">${escapeHtml(user.EMAIL || user.email || '')}</span>
                    </div>
                    <button class="add-user-btn" data-user-id="${user.USER_ID || user.user_id}">Add</button>
                </div>
            `;
        });
        html += '</div>';
        
        searchResults.innerHTML = html;
        
        // Add event listeners to add buttons
        document.querySelectorAll('.add-user-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const userId = btn.getAttribute('data-user-id');
                addUserToGroup(userId, currentGroupId, btn);
            });
        });
    }
    
    // Add user to group - updated with better error handling
    async function addUserToGroup(userId, groupId, button) {
        try {
            const response = await fetch('/ShelfControl/add-member', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `group_id=${groupId}&user_id=${userId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                button.textContent = 'Added';
                button.disabled = true;
                button.classList.add('added');
            } else {
                alert(`Error: ${data.message || 'Failed to add member'}`);
            }
        } catch (error) {
            console.error('Error adding member:', error);
            alert('An error occurred while adding member.');
        }
    }
    
    // Helper function to escape HTML for security
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});