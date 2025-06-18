<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShelfControl - Your Personal Library Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/ShelfControl/views/css/base.css">
    <link rel="stylesheet" href="/ShelfControl/views/css/landing.css">

</head>
<body class="landing-page">
    <!-- Add invisible theme button to prevent JS errors -->
    <button id="theme-button" style="display: none;" aria-hidden="true">
        <i class="ri-moon-line"></i>
    </button>

    <!-- ========================= HERO SECTION ============================= -->
    <section class="hero">
        <div class="hero__container container">
            <div class="hero__data">
                <h1 class="hero__title">
                {$heading}
            </h1>
            <p class="hero__description">
                {$subheading}
            </p>
                
                <div class="hero__buttons">
                    <button class="button" onclick="window.location.href='{$loginUrl}'">
                        Login
                    </button>
                    <a href="{$registerUrl}" class="button button--outline">
                        Register
                    </a>
                </div>
            </div>
            
            <div class="hero__image">
                <img src="/ShelfControl/assets/images/ShelfControl(2).png" alt="ShelfControl Library Management" class="hero__img">            
            </div>
        </div>
    </section>

    <!-- ========================= FEATURES SECTION ============================= -->
    <section class="features">
        <div class="features__container container">
            <h2 class="features__title">Why Choose ShelfControl?</h2>
            
            <div class="features__grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="ri-book-open-line"></i>
                    </div>
                    <h3 class="feature-card__title">Track Your Reading</h3>
                    <p class="feature-card__description">
                        Monitor your reading progress, set goals, and keep track of books you want to read next.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="ri-database-2-line"></i>
                    </div>
                    <h3 class="feature-card__title">Organize Your Library</h3>
                    <p class="feature-card__description">
                        Catalog your personal collection and access nationwide library databases effortlessly.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="ri-search-line"></i>
                    </div>
                    <h3 class="feature-card__title">Discover New Books</h3>
                    <p class="feature-card__description">
                        Get personalized recommendations and explore new genres based on your reading history.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================= CTA SECTION ============================= -->
    <section class="cta">
        <div class="container">
            <h2 class="cta__title">Ready to Start Your Reading Journey?</h2>
            <p class="cta__description">
                Join the book lovers who trust ShelfControl with their library management (just the CEOs right now).
            </p>
            <a href="{$registerUrl}" class="cta__button">
                Get Started Today
            </a>
        </div>
    </section>

    <script src="/ShelfControl/views/scripts/darkTheme.js"></script>
</body>
</html>