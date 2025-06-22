let newsData = [];

const newsList = document.getElementById('news-list');
const emptyState = document.getElementById('empty-state');
const loader = document.getElementById('loader');
const addNewsBtn = document.getElementById('add-news-btn');
const modalOverlay = document.getElementById('modal-overlay');
const modalClose = document.getElementById('modal-close');
const newsForm = document.getElementById('news-form');


function fetchNews() {

    showLoader();
    fetch('/ShelfControl/rss')
        .then(response => response.text())
        .then(str => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(str, "application/xml");
            const items = Array.from(xml.getElementsByTagName("item"));
            newsData = items.map(item => ({
                id: item.getElementsByTagName("guid")[0]?.textContent || Date.now() + Math.random(),
                type: item.getElementsByTagName("type")[0]?.textContent || 'announcement',
                title: item.getElementsByTagName("title")[0]?.textContent || '',
                description: item.getElementsByTagName("description")[0]?.textContent || '',
                author: '', // dacă nu ai author în RSS, lasă gol sau elimină din render
                date: item.getElementsByTagName("pubDate")[0]?.textContent || '',
                link: item.getElementsByTagName("link")[0]?.textContent || ''
}));
            renderNews();
            hideLoader();
        })
        .catch(() => {
            newsData = [];
            renderNews();
            hideLoader();
        });
}

function getNewsTypeIcon(type) {
    const icons = {
        review: 'ri-star-line',
        launch: 'ri-rocket-line',
        announcement: 'ri-megaphone-line',
        ranking: 'ri-trophy-line'
    };
    return icons[type] || 'ri-news-line';
}


function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function renderNews() {
    if (newsData.length === 0) {
        newsList.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    newsList.style.display = 'grid';
    emptyState.style.display = 'none';

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

function showLoader() {
    loader.classList.add('active');
    newsList.style.display = 'none';
    emptyState.style.display = 'none';
}

function hideLoader() {
    loader.classList.remove('active');
}

function openModal() {
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = '';
    newsForm.reset();
}

function addNews(newsItem) {
    showLoader();
    
    fetch('/ShelfControl/news/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(newsItem)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchNews(); 
            closeModal();
        } else {
            alert(data.message || 'Failed to add news');
        }
        hideLoader();
    })
    .catch(() => {
        alert('Error adding news');
        hideLoader();
    });
}

addNewsBtn.addEventListener('click', openModal);
modalClose.addEventListener('click', closeModal);
modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
        closeModal();
    }
});

newsForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const newsItem = {
        type: document.getElementById('news-type').value,
        title: document.getElementById('news-title').value,
        description: document.getElementById('news-description').value,
        author: document.getElementById('news-author').value,
        link: document.getElementById('news-link').value
    };

    addNews(newsItem);
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        closeModal();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    showLoader();
    fetchNews();

    setInterval(() => {
        fetchNews();
     }, 60000); // 1 minut
});
