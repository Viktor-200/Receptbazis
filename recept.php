<?php
session_start();
$conn = new mysqli("localhost", "root", "", "users_db");

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$recipe = null;
$id = 0;
$current_user_id = null;
$is_favorite = false;

if (isset($_SESSION['username'])) {
    $u_stmt = $conn->prepare("SELECT id FROM felhasznalok WHERE username = ?");
    $u_stmt->bind_param("s", $_SESSION['username']);
    $u_stmt->execute();
    $u_res = $u_stmt->get_result()->fetch_assoc();
    if ($u_res) {
        $current_user_id = $u_res['id'];
    }
    $u_stmt->close();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if (isset($_POST['toggle_favorite']) && $current_user_id) {
        $check_fav = $conn->prepare("SELECT id FROM kedvencek WHERE user_id = ? AND recept_id = ?");
        $check_fav->bind_param("ii", $current_user_id, $id);
        $check_fav->execute();
        
        if ($check_fav->get_result()->num_rows > 0) {
            $del_fav = $conn->prepare("DELETE FROM kedvencek WHERE user_id = ? AND recept_id = ?");
            $del_fav->bind_param("ii", $current_user_id, $id);
            $del_fav->execute();
        } else {
            $ins_fav = $conn->prepare("INSERT INTO kedvencek (user_id, recept_id) VALUES (?, ?)");
            $ins_fav->bind_param("ii", $current_user_id, $id);
            $ins_fav->execute();
        }
        header("Location: recept.php?id=" . $id);
        exit();
    }

    $stmt = $conn->prepare("
        SELECT r.*, f.username AS uploader_name, f.id AS uploader_id 
        FROM receptek r 
        LEFT JOIN felhasznalok f ON r.user_id = f.id 
        WHERE r.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();

        if ($current_user_id) {
            $fav_stmt = $conn->prepare("SELECT id FROM kedvencek WHERE user_id = ? AND recept_id = ?");
            $fav_stmt->bind_param("ii", $current_user_id, $id);
            $fav_stmt->execute();
            if ($fav_stmt->get_result()->num_rows > 0) {
                $is_favorite = true;
            }
            $fav_stmt->close();
        }
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
                
                <?php if ($current_user_id): ?>
                    <form method="POST" style="margin-bottom: 20px;">
                        <button type="submit" name="toggle_favorite" class="action-button" style="background-color: <?php echo $is_favorite ? '#e74c3c' : '#2ecc71'; ?>; border: none; padding: 10px 15px; border-radius: 5px; color: white; cursor: pointer;">
                            <i class="fas <?php echo $is_favorite ? 'fa-heart-broken' : 'fa-heart'; ?>"></i> 
                            <?php echo $is_favorite ? 'Eltávolítás a kedvencekből' : 'Mentés a kedvencek közé'; ?>
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (!empty($recipe['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                <?php endif; ?>

                <h3>Elkészítés:</h3>
                <div class="description-content">
                    <?php 
                    $text = htmlspecialchars($recipe['description']);
                    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                    $replacement = '<div class="video-container"><iframe src="https://www.youtube.com/embed/$1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                    $text_with_video = preg_replace($pattern, $replacement, $text);
                    echo nl2br($text_with_video);
                    ?>
                </div>
                
                <div style="margin-top: 30px; color: #aaa; font-size: 0.95em; background: #2a2a2a; padding: 15px; border-radius: 8px; display: inline-block; border: 1px solid #444;">
                    <p style="margin: 0 0 5px 0;">
                        <i class="fas fa-user" style="color: #5e9cff; margin-right: 5px;"></i> Feltöltötte: 
                        <?php if (!empty($recipe['uploader_name'])): ?>
                            <a href="profil.php?view_id=<?php echo $recipe['uploader_id']; ?>" style="color: #5e9cff; font-weight: bold; text-decoration: none;">
                                <?php echo htmlspecialchars($recipe['uploader_name']); ?>
                            </a>
                        <?php else: ?>
                            <span style="color: #888; font-style: italic;">Törölt felhasználó</span>
                        <?php endif; ?>
                    </p>
                    <p style="margin: 0;">
                        <i class="fas fa-calendar-alt" style="color: #5e9cff; margin-right: 5px;"></i> Feltöltve: <?php echo date("Y. m. d. H:i", strtotime($recipe['created_at'])); ?>
                    </p>
                </div>

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