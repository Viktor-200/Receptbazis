<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['welcome_shown']) && isset($_SESSION['username'])) {
    $_SESSION['welcome_shown'] = true;
    $showWelcome = true;
} else {
    $showWelcome = false;
}

$searchResults = [];
if (isset($_GET['search'])) {
    $conn = new mysqli("localhost", "root", "", "users_db");
    if (!$conn->connect_error) {
        $search = "%" . $_GET['search'] . "%";
        $stmt = $conn->prepare("SELECT * FROM receptek WHERE title LIKE ?");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $searchResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptbázis - Kezdőlap</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content">
        
        <?php if ($showWelcome): ?>
            <h1 id="welcomeMessage" class="fade-in">
                Üdvözlünk, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            </h1>
        <?php endif; ?>

        <?php if (!empty($searchResults)): ?>
            <div class="results-container">
                <?php foreach ($searchResults as $recipe): ?>
                    <div class="recipe-card" onclick="location.href='recept.php?id=<?php echo $recipe['id']; ?>'" style="cursor: pointer;">
                        
                        <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        
                        <?php if (!empty($recipe['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                                 style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px; margin-top: 10px;">
                        <?php endif; ?>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>

</body>
</html>