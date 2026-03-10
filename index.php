<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['username']) && !isset($_SESSION['welcome_processed'])) {
    $_SESSION['show_welcome_header'] = true;
    $_SESSION['welcome_processed'] = true; 
}

$conn = new mysqli("localhost", "root", "", "users_db");
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$categoryFilter = isset($_GET['cat']) ? $_GET['cat'] : null;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
$recipes = [];
$recommended_recipes = [];

if (!$categoryFilter && !$searchTerm) {
    $res = $conn->query("SELECT * FROM receptek ORDER BY RAND() LIMIT 5");
    if ($res) {
        $recommended_recipes = $res->fetch_all(MYSQLI_ASSOC);
    }
}

if ($categoryFilter) {
    $stmt = $conn->prepare("SELECT * FROM receptek WHERE kategoria = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $categoryFilter);
    $stmt->execute();
    $recipes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} elseif ($searchTerm) {
    $search = "%" . $searchTerm . "%";
    $stmt = $conn->prepare("SELECT * FROM receptek WHERE title LIKE ? OR description LIKE ?");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $recipes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $res = $conn->query("SELECT * FROM receptek ORDER BY RAND() LIMIT 12");
    if ($res) {
        $recipes = $res->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptbázis - <?php echo $categoryFilter ? htmlspecialchars($categoryFilter) : 'Kezdőlap'; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode index-gif-bg"> <?php include 'header.php'; ?>

    <div class="content">
        <?php if (!$categoryFilter && !$searchTerm && count($recommended_recipes) > 0): ?>
            <div class="recommendations-section">
                <h2 class="recommendations-title">Ajánlásaink</h2>
                <div class="recommendations-grid">
                    <?php foreach ($recommended_recipes as $recipe): ?>
                        <div class="recommendation-card" onclick="window.location.href='recept.php?id=<?php echo $recipe['id']; ?>'">
                            <?php if (!empty($recipe['thumbnail_path'])): ?>
                                <img src="<?php echo htmlspecialchars($recipe['thumbnail_path']); ?>" alt="Recept kép">
                            <?php elseif (!empty($recipe['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="Recept kép">
                            <?php else: ?>
                                <div class="no-image-placeholder">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($categoryFilter): ?>
            <h1 style="margin-bottom: 30px;">Kategória: <?php echo htmlspecialchars($categoryFilter); ?></h1>
        <?php elseif ($searchTerm): ?>
            <h1 style="margin-bottom: 30px;">Keresési találatok: "<?php echo htmlspecialchars($searchTerm); ?>"</h1>
        <?php endif; ?>

        <?php if ($categoryFilter || $searchTerm): ?>
            <?php if (count($recipes) > 0): ?>
                <div class="recipe-grid"> <?php foreach ($recipes as $recipe): ?>
                        <div class="recipe-card" onclick="window.location.href='recept.php?id=<?php echo $recipe['id']; ?>'">
                            <?php if (!empty($recipe['thumbnail_path'])): ?>
                                <img src="<?php echo htmlspecialchars($recipe['thumbnail_path']); ?>" alt="Recept kép">
                            <?php elseif (!empty($recipe['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="Recept kép">
                            <?php else: ?>
                                <div class="no-image-placeholder">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="recipe-info">
                                <span class="recipe-category"><?php echo htmlspecialchars($recipe['kategoria']); ?></span>
                                <h3 style="margin-top: 5px;"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                <p style="color: #888; font-size: 0.85em;">
                                    <i class="far fa-clock"></i> <?php echo date("Y.m.d.", strtotime($recipe['created_at'])); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-utensils" style="font-size: 3rem; color: #444; margin-bottom: 20px;"></i>
                    <p style="color: #888; font-size: 1.2em;">Sajnos nem találtunk ilyen receptet.</p>
                    <a href="index.php" style="color: #5e9cff; text-decoration: none; display: inline-block; margin-top: 15px;">
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($categoryFilter || $searchTerm): ?>
            <p style="margin-top: 40px; text-align: center;">
                <a href="index.php" style="color: #5e9cff; text-decoration: none; background: #333; padding: 10px 20px; border-radius: 20px;">Vissza a főoldalra</a>
            </p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>