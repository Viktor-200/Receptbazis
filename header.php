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
            <a id="signoutBtn" href="login.php">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</div>