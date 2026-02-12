<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "users_db");
$conn->set_charset("utf8mb4");

$current_logged_in_username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM felhasznalok WHERE username = ?");
$stmt->bind_param("s", $current_logged_in_username);
$stmt->execute();
$current_user_res = $stmt->get_result()->fetch_assoc();
$current_user_id = $current_user_res['id'];

$target_profile_id = $current_user_id;
$is_own_profile = true;

if (isset($_GET['view_id'])) {
    $target_profile_id = intval($_GET['view_id']);
    if ($target_profile_id !== $current_user_id) {
        $is_own_profile = false;
    }
}

if (isset($_GET['delete_recipe_id']) && $is_own_profile) {
    $delete_id = intval($_GET['delete_recipe_id']);
    $del_stmt = $conn->prepare("DELETE FROM receptek WHERE id = ? AND user_id = ?");
    $del_stmt->bind_param("ii", $delete_id, $target_profile_id);
    $del_stmt->execute();
    header("Location: profil.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM felhasznalok WHERE id = ?");
$stmt->bind_param("i", $target_profile_id);
$stmt->execute();
$profile_data = $stmt->get_result()->fetch_assoc();

if (!$profile_data) {
    header("Location: index.php");
    exit();
}

$recipes_stmt = $conn->prepare("SELECT * FROM receptek WHERE user_id = ? ORDER BY created_at DESC");
$recipes_stmt->bind_param("i", $target_profile_id);
$recipes_stmt->execute();
$recipes_res = $recipes_stmt->get_result();

$comments_stmt = $conn->prepare("
    SELECT h.*, r.title as recept_cim 
    FROM hozzaszolasok h 
    LEFT JOIN receptek r ON h.recept_id = r.id 
    WHERE h.felhasznalo_id = ? 
    ORDER BY h.datum DESC 
    LIMIT 10");
$comments_stmt->bind_param("i", $target_profile_id);
$comments_stmt->execute();
$comments_res_prof = $comments_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - <?php echo htmlspecialchars($profile_data['username']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content">
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #333;">
            <img src="<?php echo htmlspecialchars(!empty($profile_data['profile_pic']) ? $profile_data['profile_pic'] : 'img/alap.png'); ?>" 
                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
            
            <div>
                <h1 style="margin: 0; font-size: 2em;"><?php echo htmlspecialchars($profile_data['username']); ?></h1>
                <p style="color: #888; margin-top: 5px;">Csatlakozott: <?php echo date("Y. m. d.", strtotime($profile_data['created_at'])); ?></p>
                
                <?php if ($is_own_profile): ?>
                    <a href="beallitasok.php" class="action-button" style="display: inline-block; margin-top: 10px; font-size: 0.8em; padding: 5px 15px;">
                        <i class="fas fa-cog"></i> Profil szerkesztése
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-grid">
            <section>
                <h2><i class="fas fa-utensils" style="color: #5e9cff; margin-right: 10px;"></i>Receptek</h2>
                <div style="margin-top: 20px;">
                    <?php if ($recipes_res->num_rows > 0): ?>
                        <?php while($row = $recipes_res->fetch_assoc()): ?>
                            <div class="activity-item">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <a href="recept.php?id=<?php echo $row['id']; ?>" style="color: white; font-weight: bold; text-decoration: none; font-size: 1.1em;">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </a>
                                    
                                    <?php if ($is_own_profile): ?>
                                        <div class="action-buttons">
                                            <a href="edit_recipe.php?id=<?php echo $row['id']; ?>" title="Szerkesztés" style="color: #f39c12; margin-right: 15px;"><i class="fas fa-edit"></i></a>
                                            <a href="profil.php?delete_recipe_id=<?php echo $row['id']; ?>" 
                                               onclick="return confirm('Biztosan törlöd ezt a receptet?')" 
                                               title="Törlés" style="color: #e74c3c;"><i class="fas fa-trash"></i></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 0.8em; color: #888; margin-top: 5px;">Feltöltve: <?php echo $row['created_at']; ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #666;">Ez a felhasználó még nem töltött fel receptet.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section>
                <h2><i class="fas fa-comment-alt" style="color: #5e9cff; margin-right: 10px;"></i>Legutóbbi hozzászólások</h2>
                <div style="margin-top: 20px;">
                    <?php if ($comments_res_prof->num_rows > 0): ?>
                        <?php while($com = $comments_res_prof->fetch_assoc()): ?>
                            <div class="activity-item">
                                <div style="font-size: 0.8em; color: #5e9cff; margin-bottom: 5px;">
                                    <a href="recept.php?id=<?php echo $com['recept_id']; ?>" style="color: #5e9cff; text-decoration: none;">
                                        Recept: <?php echo htmlspecialchars($com['recept_cim'] ?? 'Törölt recept'); ?>
                                    </a>
                                </div>
                                <p style="margin: 0; font-style: italic;">"<?php echo htmlspecialchars($com['szoveg']); ?>"</p>
                                <div style="font-size: 0.75em; color: #666; margin-top: 8px; text-align: right;">
                                    <?php echo $com['datum']; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #666;">Még nincs hozzászólás.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>