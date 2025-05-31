<section class="social-page section">
    <div class="container">
        <div class="social-page__header">
            <h1 class="social-page__title">Reading Groups</h1>
            <button class="create-group-btn" id="createGroupBtn">
                <i class="ri-add-line"></i> Create New Group
            </button>
        </div>
        
        <!-- Groups Section -->
        <div class="groups-section">
            <?php if (empty($groups)): ?>
                <p class="empty-message">You haven't joined any reading groups yet.</p>
            <?php else: ?>
                <div class="groups-grid">
                    <?php foreach ($groups as $group): ?>
                        <div class="group-card">
                            <h3 class="group-card__name"><?php echo htmlspecialchars($group['GROUP_NAME']); ?></h3>
                            <div class="group-card__meta">
                                <div class="group-meta-item">
                                    <i class="ri-user-line"></i>
                                    <span><?php echo $group['MEMBER_COUNT']; ?> members</span>
                                </div>
                                <div class="group-meta-item">
                                    <i class="ri-user-star-line"></i>
                                    <span>Created by: <?php echo htmlspecialchars($group['CREATOR_NAME']); ?></span>
                                </div>
                            </div>
                            <?php if ($group['CREATOR_ID'] == $userId): ?>
                                <div class="group-actions">
                                    <button class="add-member-btn" data-group-id="<?php echo $group['GROUP_ID']; ?>">
                                        <i class="ri-user-add-line"></i> Add Members
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Create Group Modal -->
    <div class="modal-overlay" id="createGroupModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Create Reading Group</h2>
                <button class="modal-close" id="closeCreateGroupModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="createGroupForm">
                    <div class="form-group">
                        <label for="groupName">Group Name</label>
                        <input type="text" id="groupName" name="name" required>
                    </div>
                    <button type="submit" class="btn-primary">Create Group</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Member Modal -->
    <div class="modal-overlay" id="addMemberModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Add Members</h2>
                <button class="modal-close" id="closeAddMemberModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="search-container">
                    <input type="text" id="userSearchInput" placeholder="Search by email...">
                    <button id="searchUserBtn">Search</button>
                </div>
                <div id="searchResults" class="search-results"></div>
            </div>
        </div>
    </div>
</section>