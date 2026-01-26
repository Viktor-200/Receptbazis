<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM felhasznalok WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Helytelen jelszó.";
        }
    } else {
        $error = "Nem található ilyen felhasználó.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode auth-page">

<div class="form-container">
    <h2>Bejelentkezés</h2>
    
    <?php if ($error): ?>
        <p style="color: #ff6b6b; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Bejelentkezés</button>
    </form>
    
    <p>Nincs még fiókod? <a href="signup.php">Regisztrálj itt</a></p>
</div>

</body>
</html>