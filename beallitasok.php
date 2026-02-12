<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "users_db");
$conn->set_charset("utf8mb4");

$message = "";
$error = "";
$current_user = $_SESSION['username'];

if (!isset($_SESSION['profile_pic'])) {
    $stmt = $conn->prepare("SELECT profile_pic FROM felhasznalok WHERE username = ?");
    $stmt->bind_param("s", $current_user);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $_SESSION['profile_pic'] = !empty($res['profile_pic']) ? $res['profile_pic'] : 'img/alap.png';
}
$profile_pic = $_SESSION['profile_pic'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "user_" . time() . "_" . rand(1000,9999) . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE felhasznalok SET profile_pic = ? WHERE username = ?");
            $stmt->bind_param("ss", $target_file, $current_user);
            if ($stmt->execute()) {
                $_SESSION['profile_pic'] = $target_file;
                $profile_pic = $target_file;
                $message = "Profilkép sikeresen frissítve!";
            }
        }
    }

    if (!empty($_POST['new_username']) && $_POST['new_username'] !== $current_user) {
        $new_un = htmlspecialchars($_POST['new_username']);
        $stmt = $conn->prepare("UPDATE felhasznalok SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_un, $current_user);
        if ($stmt->execute()) {
            $_SESSION['username'] = $new_un;
            $current_user = $new_un;
            $message = "Felhasználónév sikeresen módosítva!";
        } else {
            $error = "A felhasználónév már foglalt!";
        }
    }

    if (!empty($_POST['new_password'])) {
        $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE felhasznalok SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_pass, $current_user);
        $stmt->execute();
        $message = "Jelszó sikeresen módosítva!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beállítások - Receptbázis</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content" style="display: flex; justify-content: center; padding-top: 40px;">
        <div class="form-container" style="max-width: 500px; width: 100%;">
            <h2>Fiók beállítások</h2>

            <?php if ($message): ?>
                <p style="color: #2ecc71; margin-bottom: 15px; background: rgba(46, 204, 113, 0.1); padding: 10px; border-radius: 5px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <?php if ($error): ?>
                <p style="color: #e74c3c; margin-bottom: 15px; background: rgba(231, 76, 60, 0.1); padding: 10px; border-radius: 5px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="beallitasok.php" enctype="multipart/form-data">
                
                <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 30px;">
                    <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profilkép" 
                         style="width: 130px; height: 130px; border-radius: 50%; border: 3px solid #5e9cff; object-fit: cover; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                    
                    <label for="profile_image" class="action-button" style="display: inline-block; cursor: pointer; padding: 8px 20px; font-size: 0.9em;">
                        <i class="fas fa-camera"></i> Kép cseréje
                    </label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;" onchange="document.getElementById('file-name').innerText = 'Kiválasztva: ' + this.files[0].name">
                    <div id="file-name" style="font-size: 0.8em; color: #aaa; margin-top: 8px; font-style: italic;"></div>
                </div>

                <hr style="border: 0; border-top: 1px solid #444; margin: 20px 0;">

                <label for="new_username">Felhasználónév módosítása:</label>
                <input type="text" name="new_username" id="new_username" placeholder="<?php echo htmlspecialchars($current_user); ?>" style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #444; background: #333; color: white;">

                <label for="new_password">Új jelszó megadása:</label>
                <input type="password" name="new_password" id="new_password" placeholder="Hagyja üresen, ha nem módosítja" style="width: 100%; padding: 10px; margin-bottom: 25px; border-radius: 5px; border: 1px solid #444; background: #333; color: white;">

                <button type="submit" class="action-button" style="width: 100%; padding: 12px; font-weight: bold;">Módosítások mentése</button>
            </form>
            
            <p style="margin-top: 20px;">
                <a href="profil.php" style="color: #5e9cff; text-decoration: none;"><i class="fas fa-arrow-left"></i> Vissza a profilomhoz</a>
            </p>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>