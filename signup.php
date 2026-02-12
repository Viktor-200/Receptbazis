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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO felhasznalok (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "Hiba történt a regisztráció során: " . $conn->error;
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
    <title>Regisztráció</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode auth-page">

<div class="form-container">
    <h2>Regisztráció</h2>
    
    <form method="POST" action="signup.php">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Regisztráció</button>
    </form>
    
    <p>Már van fiókod? <a href="login.php">Jelentkezz be</a></p>
</div>

</body>
</html>