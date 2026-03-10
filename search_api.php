<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "users_db");

if ($conn->connect_error) {
    die(json_encode([]));
}

$conn->set_charset("utf8mb4");

if (isset($_GET['term'])) {
    $search = "%" . $_GET['term'] . "%";
    $stmt = $conn->prepare("SELECT id, title, thumbnail_path, image_path FROM receptek WHERE title LIKE ? LIMIT 5");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'image_path' => !empty($row['thumbnail_path']) ? $row['thumbnail_path'] : $row['image_path']
        ];
    }
    
    echo json_encode($recipes);
    $stmt->close();
} else {
    echo json_encode([]);
}
$conn->close();
?>