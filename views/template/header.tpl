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
           <div class="profile grid" id="profile-content">
                <form action="/ShelfControl/profile/update" method="POST" class="profile__form">
                    <h3 class="profile__title"> Profile </h3>

                    <div class="profile__group grid">
                        <div>
                            <label for="profile-username" class="profile__label">Username</label>
                            <input type="text" name="username" id="profile-username" class="profile__info" 
                                   value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="profile-email" class="profile__label">Email</label>
                            <input type="email" name="email" id="profile-email" class="profile__info" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                        
                        <div>
                            <button type="submit" class="button">Update Profile</button>
                        </div>
                    </div>
                </form>

                <i class="ri-close-line profile__close" id="profile-close"></i>
           </div>

        <!-- ========================== MAIN ============================== -->
         <main class="main">