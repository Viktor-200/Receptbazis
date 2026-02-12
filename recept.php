<?php
session_start();
$conn = new mysqli("localhost", "root", "", "users_db");

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

$recipe = null;
$id = 0;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM receptek WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recipe ? htmlspecialchars($recipe['title']) : 'Recept nem található'; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content">
        <?php if ($recipe): ?>
            <div class="recipe-detail">
                <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
                
                <?php if (!empty($recipe['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                <?php endif; ?>

                <h3>Elkészítés / Leírás:</h3>
                <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                
                <p style="margin-top: 20px; color: #888; font-size: 0.9em;">
                    Feltöltve: <?php echo $recipe['created_at']; ?>
                </p>

                <?php 
                    $page_type = 'recept'; 
                    $page_id = $id; 
                    include 'kommentek.php'; 
                ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px;">
                <h2>A keresett recept nem található.</h2>
                <a href="index.php" style="color: #5e9cff;">Vissza a főoldalra</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>
<?php $conn->close(); ?>