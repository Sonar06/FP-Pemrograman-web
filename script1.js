const API_KEY = "a21659e843174f87ba58d8025b8021da";
//const API_KEY = "0ee8b934d92f2fe16433eed1a37b1186";
const BASE_URL = "https://newsapi.org/v2";
//const BASE_URL = "https://gnews.io/api/v4";

// Elemen HTML
const heroSection = document.querySelector(".hero-section");
const newsGrid = document.querySelector(".row.g-4");
const searchForm = document.querySelector("form[role='search']");
const searchInput = document.getElementById("searchInput");

// Ambil berita utama + berita grid
async function loadTopNews() {
    try {
        const res = await fetch(`${BASE_URL}/top-headlines?country=us&apiKey=${API_KEY}`);
        //const res = await fetch(`${BASE_URL}/top-headlines?category=general&lang=id&country=id&max=10&apikey=${API_KEY}`);
        const data = await res.json();

        if (!data.articles || data.articles.length === 0) return;

        const hero = data.articles[0];
        setHero(hero);

        const otherNews = data.articles.slice(1, 4);
        setNewsGrid(otherNews);

    } catch (err) {
        console.error("Gagal memuat berita:", err);
    }
}

// Render hero section
function setHero(article) {
    heroSection.style.backgroundImage =
        `linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('${article.image}')`;

    heroSection.innerHTML = `
        <a href="${article.url}" target="_blank" class="stretched-link"></a>
        <div>
            <span class="badge bg-danger mb-2">BERITA UTAMA</span>
            <h1 class="fw-bold display-6">${article.title}</h1>
            <div class="small text-light opacity-75 mt-2">
                <i class="bi bi-person-fill me-1"></i> ${article.source.name} • 
                <i class="bi bi-calendar me-1"></i> ${new Date(article.publishedAt).toLocaleDateString("id-ID")}
            </div>
        </div>
    `;
}

// Render grid 3 berita
function setNewsGrid(articles) {
    newsGrid.innerHTML = "";

    articles.forEach((a) => {
        newsGrid.innerHTML += `
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <img src="${a.image}" class="card-img-top" alt="Berita">
                        <span class="position-absolute top-0 start-0 badge bg-danger m-2">${a.source.name}</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-2">
                            <i class="bi bi-calendar3"></i> ${new Date(a.publishedAt).toLocaleDateString("id-ID")}
                        </small>
                        <h5 class="card-title fw-bold">
                            <a href="${a.url}" target="_blank" class="text-decoration-none text-dark">
                                ${a.title}
                            </a>
                        </h5>
                        <p class="card-text text-secondary small line-clamp-3">${a.description || ""}</p>
                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <small class="text-muted">${a.source.name}</small>
                            <a href="${a.url}" target="_blank" class="text-danger text-decoration-none fw-bold small">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
}

// Pencarian berita
searchForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const query = searchInput.value.trim();
    if (!query) return;

    try {
        const res = await fetch(`${BASE_URL}/search?q=${encodeURIComponent(query)}&lang=id&country=id&max=9&apikey=${API_KEY}`);
        const data = await res.json();

        if (!data.articles || data.articles.length === 0) {
            alert("Tidak ada hasil ditemukan.");
            return;
        }

        // Tampilkan hasil
        setHero(data.articles[0]);
        setNewsGrid(data.articles.slice(1, 4));

    } catch (err) {
        console.error("Gagal mencari berita:", err);
    }
});

// Load berita saat halaman dibuka
loadTopNews();
