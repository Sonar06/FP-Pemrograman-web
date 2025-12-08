// --- KONFIGURASI ---
const API_KEY = 'a21659e843174f87ba58d8025b8021da'; 

// Elemen HTML
const mainGridContainer = document.getElementById('main-grid-container');
const lowerGridContainer = document.getElementById('lower-grid-container');
const footerContainer = document.getElementById('footer-container');
const searchInput = document.getElementById('search-input');

// --- FUNGSI UTAMA (INIT) ---
// Fungsi ini dipanggil saat tombol cari ditekan atau web dibuka
function initApp(query = 'amerika serikat') {
    fetchRecentNews(query);  // 1. Ambil berita TERBARU untuk atas
    fetchPopularNews(query); // 2. Ambil berita POPULER untuk footer
}

// --- BAGIAN 1: AMBIL BERITA TERBARU (Main & Lower Grid) ---
async function fetchRecentNews(query) {
    if(mainGridContainer) mainGridContainer.innerHTML = '<p style="padding:20px;">Memuat berita terbaru...</p>';

    try {
        // sortBy=publishedAt artinya mengurutkan dari yang paling baru
        const url = `https://newsapi.org/v2/everything?q=${query}&language=en&sortBy=publishedAt&apiKey=${API_KEY}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.status === "ok") {
            renderMainContent(data.articles);
        }
    } catch (error) {
        console.error("Error Recent:", error);
    }
}

// --- BAGIAN 2: AMBIL BERITA POPULER (Footer) ---
async function fetchPopularNews(query) {
    if(footerContainer) footerContainer.innerHTML = '<p>Memuat populer...</p>';

    try {
        // sortBy=popularity artinya mengurutkan berdasarkan popularitas sumber
        const url = `https://newsapi.org/v2/everything?q=${query}&sortBy=popularity&pageSize=3&apiKey=${API_KEY}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.status === "ok") {
            renderFooterContent(data.articles);
        }
    } catch (error) {
        console.error("Error Popular:", error);
    }
}

// --- FUNGSI RENDER TAMPILAN UTAMA ---
function renderMainContent(articles) {
    // Filter berita rusak
    const validArticles = articles.filter(art => art.urlToImage && art.title);

    if (validArticles.length < 4) {
        console.warn("Berita tidak cukup.");
        return;
    }

    // 1. Render BIG NEWS (Index 0)
    const bigNews = validArticles[0];
    let mainHTML = `
        <div class="big-news">
            <img src="${bigNews.urlToImage}" alt="News">
            <a href="${bigNews.url}" target="_blank" style="text-decoration:none; color:inherit;">
                <h2>${bigNews.title}</h2>
            </a>
            <p class="time">${formatTime(bigNews.publishedAt)}</p>
        </div>
    `;

    // 2. Render SMALL NEWS (Index 1, 2, 3)
    const smallNewsList = validArticles.slice(1, 4);
    smallNewsList.forEach(news => {
        mainHTML += `
            <div class="small-news">
                <img src="${news.urlToImage}" alt="News">
                <a href="${news.url}" target="_blank" style="text-decoration:none; color:inherit;">
                    <h4>${news.title}</h4>
                </a>
                <p class="time">${formatTime(news.publishedAt)}</p>
            </div>
        `;
    });
    mainGridContainer.innerHTML = mainHTML;

    // 3. Render LOWER GRID (Index 4, 5, 6)
    const lowerNewsList = validArticles.slice(4, 7);
    let lowerHTML = '';
    lowerNewsList.forEach(news => {
        lowerHTML += `
            <div>
                <a href="${news.url}" target="_blank" style="text-decoration:none; color:inherit;">
                    <h3>${news.title}</h3>
                </a>
                <p class="time">${formatTime(news.publishedAt)}</p>
            </div>
        `;
    });
    lowerGridContainer.innerHTML = lowerHTML;
}

// --- FUNGSI RENDER FOOTER (POPULER) ---
function renderFooterContent(articles) {
    // Kita hanya butuh 2 berita untuk footer
    const popularNews = articles.slice(0, 2);
    let footerHTML = '';

    popularNews.forEach((news, index) => {
        footerHTML += `
            <div class="footer-box">
                <h2>${index + 1}</h2> <p>
                    <a href="${news.url}" target="_blank" style="text-decoration:none; color:inherit;">
                        ${news.title}
                    </a>
                </p>
                <small style="color:gray;">${news.source.name}</small>
            </div>
        `;
    });

    footerContainer.innerHTML = footerHTML;
}

// --- HELPER TIME ---
function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
}

// --- EVENT LISTENER ---
if (searchInput) {
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            initApp(searchInput.value);
        }
    });
}

// Jalankan Saat Pertama Buka
initApp('indonesia');