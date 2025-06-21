<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore</title>
</head>
<body>
<!-- =================== LIBRARY HEADER ================================= -->
<section class="library-header section" id="library">
    <div class="library-header__container container"> 
            <h1 class="library-header__title">Explore</h1>
            <p class="library-header__description">
                Explore your personal collection of books and discover new reads.
            </p>
        <div class="library-filter">
            <input type="text" id="filter-author" class="library-filter__input" placeholder="Filtrează după autor">
            <div style="position:relative;">
                <input type="text" id="filter-genre" class="library-filter__input" placeholder="Filtrează după gen" autocomplete="off" list="genre-list">
                <datalist id="genre-list">
                 <!--aici vin filtrele -->
                </datalist>
                <div id="genre-suggestions" class="dropdown-content" style="display:none; position:absolute; width:100%; z-index:10;"></div>
            </div>
            <button id="apply-filter-btn" class="library-filter__btn">Aplică filtre</button>
        </div>
    </div>
</section>

<!-- =================== BOOK LIBRARY ================================= -->
<section class="book-library section">
    <div class="book-library__container container grid" id="books-container">
        <!-- Cărțile vor fi generate aici -->
    </div>
</section>
</body>
</html>