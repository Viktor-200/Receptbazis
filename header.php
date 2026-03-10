<?php
if (isset($_SESSION['username']) && !isset($_SESSION['profile_pic'])) {
    $conn_h = new mysqli("localhost", "root", "", "users_db");
    $stmt_h = $conn_h->prepare("SELECT profile_pic FROM felhasznalok WHERE username = ?");
    $stmt_h->bind_param("s", $_SESSION['username']);
    $stmt_h->execute();
    $res_h = $stmt_h->get_result()->fetch_assoc();
    $_SESSION['profile_pic'] = !empty($res_h['profile_pic']) ? $res_h['profile_pic'] : 'img/alap.png';
    $conn_h->close();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<style>
.sidebar .material-symbols-outlined {
    margin-right: 15px;
    width: 25px;
    text-align: center;
    vertical-align: middle;
    font-size: 22px;
}
.category-list li a {
    display: flex;
    align-items: center;
}
</style>

<div id="sideMenu" class="sidebar">
    <div class="sidebar-header">
        <button id="closeMenu" class="menu-icon-btn"><i class="fas fa-times"></i></button>
        <h3>Kategóriák</h3>
    </div>
    <ul class="category-list">
        <li>
            <a href="index.php">
                <i class="fas fa-home" style="margin-right: 15px; width: 25px; text-align: center;"></i> 
                Összes recept
            </a>
        </li>
        <hr style="border: 0; border-top: 1px solid #333; margin: 10px 0;">
        
        <li>
            <a href="index.php?cat=Reggeli">
                <span class="material-symbols-outlined">egg_alt</span> Reggelik
            </a>
        </li>
        <li>
            <a href="index.php?cat=Leves">
                <span class="material-symbols-outlined">ramen_dining</span> Levesek
            </a>
        </li>
        <li>
            <a href="index.php?cat=Főfogás">
                <span class="material-symbols-outlined">dinner_dining</span> Főfogások
            </a>
        </li>
        <li>
            <a href="index.php?cat=Saláta">
                <span class="material-symbols-outlined">psychiatry</span> Saláták / Vegán
            </a>
        </li>
        <li>
            <a href="index.php?cat=Desszert">
                <span class="material-symbols-outlined">icecream</span> Desszertek
            </a>
        </li>
        <li>
            <a href="index.php?cat=Ital">
                <span class="material-symbols-outlined">water_full</span> Italok
            </a>
        </li>
    </ul>
</div>
<div id="overlay" class="overlay"></div>

<div class="header">
    <div class="logo-container">
        <button id="menuBtn" class="menu-icon-btn">
            <i class="fas fa-bars"></i>
        </button>
        <a href="index.php">
            <img id="logo" src="img/feketelogo.png" alt="Receptbázis">
        </a>
        <a href="konyhaifogalmak.php" class="action-button">Konyhai Fogalmak</a>
    </div>

    <div class="header-search">
        <form method="GET" action="index.php" autocomplete="off">
            <input type="text" id="searchInput" name="search" placeholder="Keress receptek között..." required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <div id="searchResults" class="search-results"></div>

        <?php if (isset($_SESSION['show_welcome_header']) && $_SESSION['show_welcome_header']): ?>
            <div class="welcome-msg-header">
                Üdvözlünk, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            </div>
            <?php unset($_SESSION['show_welcome_header']); ?>
        <?php endif; ?>
    </div>

    <div class="navbar">
        <button id="toggleBtn" class="theme-toggle-btn">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="feltoltes.php" class="action-button">Feltöltés</a>
            
            <div class="user-dropdown">
                <div class="dropdown-trigger">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profilkép" class="nav-profile-pic">
                </div>
                <div class="dropdown-content">
                    <div class="dropdown-header"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                    <a href="profil.php"><i class="fas fa-user"></i> Profilom</a>
                    <a href="beallitasok.php"><i class="fas fa-cog"></i> Beállítások</a>
                    <hr>
                    <a href="?logout=true"><i class="fas fa-sign-out-alt"></i> Kijelentkezés</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="action-button">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</div>