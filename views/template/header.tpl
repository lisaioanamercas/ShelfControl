<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ==================== LOGO ============================== -->
        <link rel="shortcut icon" href="assets/img/favicon.png"  type="image/png">

        <!-- ====================ICONITE (remixincon) =========================== -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

        <!-- ==================== CSS =========================== -->
        <link rel="stylesheet" href="/ShelfControl/views/css/style.css">
        <link rel="stylesheet" href="/ShelfControl/views/css/footer.css">
        
        <!-- Additional CSS files -->
        <?php if (isset($additionalCSS)): ?>
            <?php foreach ($additionalCSS as $cssFile): ?>
                <link rel="stylesheet" href="<?php echo $cssFile; ?>">
            <?php endforeach; ?>
        <?php endif; ?>

        <title><?php echo isset($pageTitle) ? $pageTitle . ' - ShelfControl' : 'ShelfControl'; ?></title>
    </head>
    <body>
        <!-- =========================== HEADER ============================== -->
        <header class="header shadow-header" id="header">
            <nav class="nav container">
                <a href="/ShelfControl/home" class="nav__logo">
                    <i class="ri-book-open-fill"></i> ShelfControl
                </a>

                <div class="nav__menu">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a href="/ShelfControl/home" class="nav__link <?php echo ($currentPage ?? '') === 'home' ? 'active-link' : ''; ?>">
                                <i class="ri-home-2-line"></i> 
                                <span> Home </span>
                            </a>
                        </li>
                        
                        <li class="nav__item">
                            <a href="/ShelfControl/toread" class="nav__link <?php echo ($currentPage ?? '') === 'toread' ? 'active-link' : ''; ?>">
                                <i class="ri-bookmark-line"></i>
                                <span> toRead </span>
                            </a>
                        </li>

                        <li class="nav__item">
                            <a href="/ShelfControl/library" class="nav__link <?php echo ($currentPage ?? '') === 'library' ? 'active-link' : ''; ?>">
                                <i class="ri-book-line"></i>
                                <span>Library</span>
                            </a>
                        </li>

                        <li class="nav__item">
                            <a href="/ShelfControl/news" class="nav__link <?php echo ($currentPage ?? '') === 'news' ? 'active-link' : ''; ?>">
                                <i class="ri-chat-smile-3-line"></i>
                                <span>News</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav__actions">

                    <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                        <div class="admin-add-button">
                            <i class="ri-add-line admin-button" id="admin-button"></i>
                            <div class="admin-dropdown" id="admin-dropdown">
                                <a href="/ShelfControl/admin/add-book" class="admin-dropdown__item">
                                    <i class="ri-book-line"></i> Add Book
                                </a>
                                <a href="/ShelfControl/admin/manage-users" class="admin-dropdown__item">
                                    <i class="ri-group-line"></i> Manage Users
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Search button -->
                     <i class="ri-search-line search-button " id="search-button"></i>

                    <!-- User profile -->
                     <i class="ri-user-line profile-button" id = "profile-button"></i>

                     <!-- Theme button -->
                      <i class="ri-moon-line  change-teheme" id="theme-button"></i>
                </div>
            </nav>
        </header>

         <!-- ========================== SEARCH =============================== -->
          <!-- In the search section -->
            <div class="search" id="search-content">
                <form action="/ShelfControl/search" method="GET" class="search__form">
                    <i class="ri-search-line search__icon"></i>
                    <input type="search" name="query" placeholder="What book are you looking for?" class="search__input">
                </form>

                <i class="ri-close-line search__close" id="search-close"></i>
            </div>
          
            <!-- ========================== PROFILE ============================== -->
            <div class="profile" id="profile-content">
            <div class="profile__container">
                <!-- Close button -->
                <button class="profile__close" id="profile-close">
                    <i class="ri-close-line"></i>
                </button>

                <!-- Profile header -->
                <div class="profile__header">
                    <div class="profile__avatar">
                        <i class="ri-user-line"></i>
                    </div>
                    <h3 class="profile__title">My Profile</h3>
                    <p class="profile__subtitle">Manage your reading journey</p>
                </div>

                <!-- Profile content -->
                <div class="profile__content">
                    <!-- Account Information -->
                    <div class="profile__section">
                        <h4 class="profile__section-title">
                            <i class="ri-account-circle-line"></i>
                            Account Information
                        </h4>
                        <div class="profile__info-grid">
                            <div class="profile__info-item">
                                <span class="profile__label">Email Address</span>
                                <span class="profile__value"><?php echo htmlspecialchars($user['email'] ?? 'user@example.com'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Reading Statistics -->
                    <div class="profile__section">
                        <h4 class="profile__section-title">
                            <i class="ri-bar-chart-line"></i>
                            Reading Statistics
                        </h4>
                        <div class="profile__stats">
                            <div class="profile__stat">
                                <span class="profile__stat-number"><?php echo $userStats['books_read'] ?? '0'; ?></span>
                                <span class="profile__stat-label">Books Read</span>
                            </div>
                            <div class="profile__stat">
                                <span class="profile__stat-number"><?php echo $userStats['currently_reading'] ?? '0'; ?></span>
                                <span class="profile__stat-label">Currently Reading</span>
                            </div>
                            <div class="profile__stat">
                                <span class="profile__stat-number"><?php echo $userStats['want_to_read'] ?? '0'; ?></span>
                                <span class="profile__stat-label">Want to Read</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                
            </div>
        </div>

        <!-- ========================== MAIN ============================== -->
         <main class="main">