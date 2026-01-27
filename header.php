<div class="header">
    <div class="logo-container">
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
        <div id="autocomplete-results"></div>

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
            <a id="signoutBtn" href="?logout=true">Kijelentkezés</a>
        <?php else: ?>
            <a id="signoutBtn" href="login.php">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</div>