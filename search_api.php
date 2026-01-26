<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "users_db");

if ($conn->connect_error) {
    die(json_encode([]));
}

if (isset($_GET['term'])) {
    $search = "%" . $_GET['term'] . "%";
    $stmt = $conn->prepare("SELECT id, title, image_path FROM receptek WHERE title LIKE ? LIMIT 5");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    
    echo json_encode($recipes);
    $stmt->close();
} else {
    echo json_encode([]);
}
$conn->close();
?>