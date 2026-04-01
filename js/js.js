const API_KEY = '7249ac9fe8124d69ad13f313134c38a4'; 
let currentPage = 1;

async function init() {
    const home = document.getElementById('game-container');
    const trends = document.getElementById('game-list');
    const critics = document.getElementById('critique-results');

    if (home) {
        const res = await fetch(`https://api.rawg.io/api/games?key=${API_KEY}&dates=2026-01-01,2026-12-31&ordering=-rating&metacritic=50,100&page_size=8&lang=fr`);
        const data = await res.json();
        renderCards(data.results, home);
    }
    if (trends) {
        const res = await fetch(`https://api.rawg.io/api/games?key=${API_KEY}&dates=2026-03-01,2026-03-31&ordering=-rating&page_size=20&lang=fr`);
        const data = await res.json();
        renderCards(data.results, trends);
    }
    if (critics) searchGamesCritique(1);
}

function renderCards(games, container) {
    if (!container || !games) return;
    container.innerHTML = '';
    games.forEach(game => {
        let ratingDisplay = game.rating > 0 ? 
            `<span class="badge border border-info text-neon-blue">${game.rating.toFixed(1)}/5</span>` : 
            `<span class="badge border border-warning text-warning">NOUVEAU</span>`;

        let gameImage = game.background_image ? game.background_image : 'https://via.placeholder.com/600x400/06090f/00d2ff?text=IMAGE+NON+DISPONIBLE';

        container.innerHTML += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="glass-card h-100 p-0 overflow-hidden border-blue position-relative">
                    <button class="btn-like position-absolute top-0 end-0 m-2" onclick="toggleLike(this, ${game.id})" style="z-index:10;">
                        <i class="fas fa-heart"></i>
                    </button>
                    <div onclick="showDetails(${game.id})" style="cursor:pointer">
                        <img src="${gameImage}" class="w-100" style="height: 180px; object-fit: cover;">
                        <div class="p-3 text-center">
                            <h6 class="text-white fw-bold mb-2">${game.name}</h6>
                            ${ratingDisplay}
                        </div>
                    </div>
                </div>
            </div>`;
    });
}

function toggleLike(btn, gameId) {
    btn.classList.toggle('active');
}

async function showDetails(id) {
    const modalElement = document.getElementById('gameModal');
    if (!modalElement) return;
    const modal = new bootstrap.Modal(modalElement);
    const res = await fetch(`https://api.rawg.io/api/games/${id}?key=${API_KEY}&lang=fr`);
    const game = await res.json();
    
    // --- LIGNE AJOUTÉE POUR LE FORMULAIRE ---
    if (document.getElementById('formGameName')) {
        document.getElementById('formGameName').value = game.name;
    }
    // ----------------------------------------

    document.getElementById('modalTitle').innerText = game.name;
    document.getElementById('modalImg').src = game.background_image || 'https://via.placeholder.com/600x400/06090f/00d2ff?text=IMAGE+NON+DISPONIBLE';
    document.getElementById('modalRating').innerText = game.rating > 0 ? game.rating : "N/A";
    
    let desc = game.description || "Aucun résumé disponible.";
    const isFrench = /[àâäéèêëïîôöùûüç]/i.test(desc);
    
    let infoTraduction = "";
    if (!isFrench && game.description_raw) {
        const googleTranslateUrl = `https://translate.google.com/?sl=auto&tl=fr&text=${encodeURIComponent(game.description_raw.substring(0, 1000))}&op=translate`;
        infoTraduction = `<div class="mt-3 p-2 border border-warning rounded"><a href="${googleTranslateUrl}" target="_blank" class="btn btn-sm btn-outline-warning w-100">TRADUIRE EN FRANÇAIS</a></div>`;
    }

    document.getElementById('modalDesc').innerHTML = desc + infoTraduction;

    const movieRes = await fetch(`https://api.rawg.io/api/games/${id}/movies?key=${API_KEY}`);
    const movieData = await movieRes.json();
    const trailerDiv = document.getElementById('modalTrailer');
    
    if (movieData.results && movieData.results.length > 0) {
        trailerDiv.innerHTML = `<video controls width="100%" autoplay muted><source src="${movieData.results[0].data.max}" type="video/mp4"></video>`;
    } else {
        trailerDiv.innerHTML = `<img src="${game.background_image_additional || game.background_image || 'https://via.placeholder.com/600x400/06090f/00d2ff?text=IMAGE+NON+DISPONIBLE'}" class="w-100 rounded">`;
    }
    modal.show();
}

async function searchGamesCritique(page) {
    const query = document.getElementById('searchCritique')?.value || '';
    const genre = document.getElementById('genreFilter')?.value || '';
    const results = document.getElementById('critique-results');
    if (!results) return;

    currentPage = page;
    const disp = document.getElementById('pageDisplay');
    if (disp) disp.innerText = `Page ${currentPage}`;

    let url = `https://api.rawg.io/api/games?key=${API_KEY}&page_size=12&page=${page}&lang=fr`;
    if (query) url += `&search=${query}`;
    if (genre) url += `&genres=${genre}`;

    const res = await fetch(url);
    const data = await res.json();
    renderCards(data.results, results);
}

function changePage(direction) {
    if (currentPage + direction > 0) {
        searchGamesCritique(currentPage + direction);
        window.scrollTo(0, 0);
    }
}

document.addEventListener('DOMContentLoaded', init);