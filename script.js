const toggleBtn = document.getElementById("toggleBtn");
const themeIcon = document.getElementById("themeIcon");
const logo = document.getElementById("logo");

let isDarkMode = localStorage.getItem("darkMode") === "true";

function applyTheme() {
    if (isDarkMode) {
        document.body.classList.add("dark-mode");
        document.body.classList.remove("light-mode");
        if(themeIcon) {
            themeIcon.classList.remove("fa-moon");
            themeIcon.classList.add("fa-sun");
        }
        if(logo) logo.src = "img/feketelogo.png";
    } else {
        document.body.classList.add("light-mode");
        document.body.classList.remove("dark-mode");
        if(themeIcon) {
            themeIcon.classList.remove("fa-sun");
            themeIcon.classList.add("fa-moon");
        }
        if(logo) logo.src = "img/feherlogo.png";
    }
}

applyTheme();

if(toggleBtn) {
    toggleBtn.addEventListener("click", () => {
        isDarkMode = !isDarkMode;
        localStorage.setItem("darkMode", isDarkMode);
        applyTheme();
    });
}

function updateClock() {
    const clockElement = document.getElementById('real-time-clock');
    if (clockElement) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clockElement.textContent = `${hours}:${minutes}:${seconds}`;
    }
}

setInterval(updateClock, 1000);
updateClock();

window.addEventListener('load', () => {
    const msg = document.getElementById("welcomeMessage");
    if (msg) {
        setTimeout(() => msg.classList.add("fade-out"), 2000);
    }
});

const searchInput = document.getElementById('searchInput');
const resultsDiv = document.getElementById('autocomplete-results');

if (searchInput && resultsDiv) {
    searchInput.addEventListener('input', function() {
        const term = this.value;

        if (term.length < 2) {
            resultsDiv.innerHTML = '';
            return;
        }

        fetch(`search_api.php?term=${encodeURIComponent(term)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                
                if (data.length > 0) {
                    data.forEach(recipe => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-item';
                        
                        const imgHTML = recipe.image_path 
                            ? `<img src="${recipe.image_path}" alt="${recipe.title}">` 
                            : '';
                        
                        div.innerHTML = `${imgHTML}<span>${recipe.title}</span>`;
                        
                        div.addEventListener('click', () => {
                            window.location.href = `recept.php?id=${recipe.id}`;
                        });
                        
                        resultsDiv.appendChild(div);
                    });
                }
            })
            .catch(err => console.error('Hiba:', err));
    });

    document.addEventListener('click', (e) => {
        if (e.target !== searchInput && e.target !== resultsDiv) {
            resultsDiv.innerHTML = '';
        }
    });
}