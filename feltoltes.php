<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$conn = new mysqli("localhost", "root", "", "users_db");
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $u_stmt = $conn->prepare("SELECT id FROM felhasznalok WHERE username = ?");
    $u_stmt->bind_param("s", $_SESSION['username']);
    $u_stmt->execute();
    $u_res = $u_stmt->get_result()->fetch_assoc();
    $user_id = $u_res['id'];
    $u_stmt->close();

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO receptek (title, description, image_path, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $image_path, $user_id);
    
    if ($stmt->execute()) {
        $message = "Recept sikeresen feltöltve!";
    } else {
        $message = "Hiba történt a mentéskor: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Új recept feltöltése</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content" style="display: flex; justify-content: center; padding-top: 50px;">
        <div class="form-container" style="max-width: 600px;">
            <h2>Új recept feltöltése</h2>
            
            <?php if ($message): ?>
                <p style="color: #5e9cff; margin-bottom: 15px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="POST" action="feltoltes.php" enctype="multipart/form-data">
                <label for="title">Étel neve (Cím):</label>
                <input type="text" id="title" name="title" required placeholder="Pl. Rakott krumpli">

                <label for="description">Leírás (Elkészítés):</label>
                <textarea id="description" name="description" rows="6" required placeholder="Ide írd a recept leírását..." style="width: 100%; background: #444; color: white; border: none; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-family: inherit;"></textarea>

                <label for="image">Kép feltöltése:</label>
                <input type="file" id="image" name="image" accept="image/*">

                <button type="submit" class="action-button" style="width: 100%; margin-top: 20px;">Recept beküldése</button>
            </form>
        </div>
    </div>

<?php include 'footer.php'; ?>
    <script src="script.js"></script> 
</body>
</html>