const toggleBtn = document.getElementById("toggleBtn");
const themeIcon = document.getElementById("themeIcon");
const logo = document.getElementById("logo");

let isDarkMode = localStorage.getItem("darkMode") === "true";

function applyTheme() {
    document.body.style.opacity = "0.7";
    
    setTimeout(() => {
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
        
        document.body.style.opacity = "1";
    }, 150);
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

document.addEventListener('DOMContentLoaded', function() {
    
    const searchInput = document.getElementById('searchInput');
    const resultsDiv = document.getElementById('searchResults');

    if (searchInput && resultsDiv) {
        searchInput.addEventListener('input', () => {
            const term = searchInput.value.trim();
            if (term.length < 2) {
                resultsDiv.innerHTML = '';
                return;
            }

            fetch(`search_api.php?term=${encodeURIComponent(term)}`)
                .then(res => res.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(recipe => {
                            const div = document.createElement('div');
                            div.className = 'search-item';
                            
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
                .catch(err => console.error('Hiba a keresés során:', err));
        });

        document.addEventListener('click', (e) => {
            if (e.target !== searchInput && e.target !== resultsDiv) {
                resultsDiv.innerHTML = '';
            }
        });
    }

    const dropdownTrigger = document.querySelector('.dropdown-trigger');
    const dropdownContent = document.querySelector('.dropdown-content');

    if (dropdownTrigger && dropdownContent) {
        dropdownTrigger.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdownContent.classList.toggle('show');
        });

        window.addEventListener('click', function(event) {
            if (!dropdownTrigger.contains(event.target)) {
                if (dropdownContent.classList.contains('show')) {
                    dropdownContent.classList.remove('show');
                }
            }
        });
    }

    const menuBtn = document.getElementById('menuBtn');
    const closeMenu = document.getElementById('closeMenu');
    const sideMenu = document.getElementById('sideMenu');
    const overlay = document.getElementById('overlay');

    function toggleSidebar() {
        if (sideMenu && overlay) {
            sideMenu.classList.toggle('open');
            overlay.classList.toggle('show');
        }
    }

    if(menuBtn) menuBtn.addEventListener('click', toggleSidebar);
    if(closeMenu) closeMenu.addEventListener('click', toggleSidebar);
    if(overlay) overlay.addEventListener('click', toggleSidebar);

    window.addEventListener('keydown', (e) => {
        if(e.key === "Escape" && sideMenu && sideMenu.classList.contains('open')) {
            toggleSidebar();
        }
    });
});