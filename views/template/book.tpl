<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$book_title}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/ShelfControl/views/css/book.css">
</head>
<body>

    <header>
        <h1>{$book_title}</h1>
        <p><strong>Autor:</strong> {$book_author}</p>
        <p><strong>Gen:</strong> {$book_genre}</p>
        <p><strong>Publicat:</strong> {$book_publication_year}</p>
    </header>

    <div class="book-details">
        <div class="book-image">
            <img src="{$book_image_url}" alt="{$book_title}" />
        </div>

        <div class="book-description">
            <h2>Descriere</h2>
            <p>{$book_description}</p>
        </div>

    <!--    <div class="book-extra-details">
            <h3>Detalii suplimentare</h3>
            <ul>
                <li><strong>Număr de pagini:</strong> {$book_page_count}</li>
                <li><strong>ISBN:</strong> {$book_isbn}</li>
                <li><strong>Limba:</strong> {$book_language}</li>
            </ul>
        </div>
    </div> -->

    <div class="reviews">
        <h3>Recenzii</h3>
        <ul>
            {foreach $reviews as $review}
                <li>
                    <p><strong>{$review_author}</strong> ({$review_date}):</p>
                    <p>{$review_text}</p>
                </li>
            {/foreach}
        </ul>
    </div>

    <footer>
        <p>&copy; 2025 Biblioteca Online. Toate drepturile rezervate.</p>
    </footer>

</body>
</html>
