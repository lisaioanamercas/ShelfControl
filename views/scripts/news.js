let newsData = [
    {
        id: 1,
        type: 'review',
        title: 'New Review: "Tehnologii Web - UAIC"',
        description: 'Fa-ma Doamne piatra tare sa rezist la suparareeeeeee ca de la atata durere simt ca nu mai am putereeeeeeeeeeeeeeee',
        author: 'elisa & daria',
        date: '2025-05-29',
        link: '#review-1'
    },
    {
        id: 2,
        type: 'launch',
        title: 'New Release: "La capsuni in spania!"',
        description: 'Un nou ghid facut special pentru studentii de la Informatica in ani terminali (pe cei de anul 1 inca ii lasam sa viseze).',
        author: 'Administratia romana de shoparle',
        date: '2025-05-28',
        link: '#book-launch-1'
    },
    {
        id: 3,
        type: 'announcement',
        title: 'Vreau sa ma las de facultate simt ca ma face la psihic',
        description: 'Daca as avea o masina a timpului m-as intoarce in trecut cand s-a gandit mama sa mai faca un copil si i-as fi spus sa stea cuminte ca nu e chiar flower power situatia la web.',
        author: 'Eu',
        date: '2025-05-27',
        link: null
    },
    {
        id: 4,
        type: 'ranking',
        title: 'Ranking cele mai nasoale materii de la facultate',
        description: 'WEB -- Programare avansata (aici nu in includ pe Irimia <3) -- SGBD -- IP !!! (iubesc arhivele zip) -- Cripto.',
        author: 'Tot eu',
        date: '2025-05-26',
        link: '#rankings'
    }
];

// DOM elements
const newsList = document.getElementById('news-list');
const emptyState = document.getElementById('empty-state');
const loader = document.getElementById('loader');
const addNewsBtn = document.getElementById('add-news-btn');
const modalOverlay = document.getElementById('modal-overlay');
const modalClose = document.getElementById('modal-close');
const newsForm = document.getElementById('news-form');

// Get news type icon
function getNewsTypeIcon(type) {
    const icons = {
        review: 'ri-star-line',
        launch: 'ri-rocket-line',
        announcement: 'ri-megaphone-line',
        ranking: 'ri-trophy-line'
    };
    return icons[type] || 'ri-news-line';
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Render news cards
function renderNews() {
    if (newsData.length === 0) {
        newsList.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    newsList.style.display = 'grid';
    emptyState.style.display = 'none';

    // Sort news by date (newest first)
    const sortedNews = [...newsData].sort((a, b) => new Date(b.date) - new Date(a.date));

    newsList.innerHTML = sortedNews.map(news => `
        <article class="news-card">
            <div class="news-card__header">
                <div class="news-card__icon news-card__icon--${news.type}">
                    <i class="${getNewsTypeIcon(news.type)}"></i>
                </div>
                <div class="news-card__content">
                    <div class="news-card__meta">
                        <span class="news-card__type">${news.type.charAt(0).toUpperCase() + news.type.slice(1)}</span>
                        <span class="news-card__date">${formatDate(news.date)}</span>
                        ${news.author ? `<span class="news-card__author">by ${news.author}</span>` : ''}
                    </div>
                    <h3 class="news-card__title">${news.title}</h3>
                    <p class="news-card__description">${news.description}</p>
                    ${news.link ? `
                        <a href="${news.link}" class="news-card__link">
                            Read more
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    ` : ''}
                </div>
            </div>
        </article>
    `).join('');
}

// Show loader
function showLoader() {
    loader.classList.add('active');
    newsList.style.display = 'none';
    emptyState.style.display = 'none';
}

// Hide loader
function hideLoader() {
    loader.classList.remove('active');
}

// Modal functions
function openModal() {
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = '';
    newsForm.reset();
}

// Add news
function addNews(newsItem) {
    showLoader();
    
    // Simulate API call
    setTimeout(() => {
        const newNews = {
            id: Date.now(),
            type: newsItem.type,
            title: newsItem.title,
            description: newsItem.description,
            author: newsItem.author || 'Anonymous',
            date: new Date().toISOString().split('T')[0],
            link: newsItem.link || null
        };

        newsData.unshift(newNews);
        hideLoader();
        renderNews();
        closeModal();
    }, 1000);
}

// Event listeners
addNewsBtn.addEventListener('click', openModal);
modalClose.addEventListener('click', closeModal);
modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
        closeModal();
    }
});

newsForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const formData = new FormData(newsForm);
    const newsItem = {
        type: document.getElementById('news-type').value,
        title: document.getElementById('news-title').value,
        description: document.getElementById('news-description').value,
        author: document.getElementById('news-author').value,
        link: document.getElementById('news-link').value
    };

    addNews(newsItem);
});

// ESC key to close modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        closeModal();
    }
});

// Initial render
document.addEventListener('DOMContentLoaded', () => {
    showLoader();
    setTimeout(() => {
        hideLoader();
        renderNews();
    }, 800);
});
