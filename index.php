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

$randomRecipes = [];
$res = $conn->query("SELECT * FROM receptek ORDER BY RAND() LIMIT 10");
if ($res) {
    $randomRecipes = $res->fetch_all(MYSQLI_ASSOC);
}

$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM receptek WHERE title LIKE ?");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $searchResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptbázis - Kezdőlap</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content">
        
        <?php if (!isset($_GET['search']) && !empty($randomRecipes)): ?>
        <div class="daily-recommendation">
            <h2 style="margin-bottom: 20px; border-left: 4px solid #5e9cff; padding-left: 15px;">Ajánlásaink</h2>
            <div class="recommendation-grid"> <?php foreach ($randomRecipes as $r): ?>
                    <div class="recommendation-card" onclick="location.href='recept.php?id=<?php echo $r['id']; ?>'">
                        <?php if (!empty($r['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($r['image_path']); ?>" alt="<?php echo htmlspecialchars($r['title']); ?>">
                        <?php else: ?>
                            <div class="no-image-placeholder">
                                <i class="fas fa-utensils"></i>
                            </div>
                        <?php endif; ?>
                        <h4><?php echo htmlspecialchars($r['title']); ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['search'])): ?>
            <h2 style="margin-bottom: 20px;">Találatok a következőre: "<?php echo htmlspecialchars($_GET['search']); ?>"</h2>
            <?php if (!empty($searchResults)): ?>
                <div class="results-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
                    <?php foreach ($searchResults as $recipe): ?>
                        <div class="recipe-card" onclick="location.href='recept.php?id=<?php echo $recipe['id']; ?>'" style="cursor: pointer; background: #2a2a2a; padding: 15px; border-radius: 12px; border: 1px solid #333;">
                            <?php if (!empty($recipe['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                                     style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                            <?php endif; ?>
                            <h3 style="color: #5e9cff; font-size: 1.1em;"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #888;">Sajnos nem találtunk ilyen receptet.</p>
            <?php endif; ?>
            <p style="margin-top: 30px;"><a href="index.php" style="color: #5e9cff; text-decoration: none;"><i class="fas fa-arrow-left"></i> Vissza az összes recepthez</a></p>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>
<?php $conn->close(); ?>