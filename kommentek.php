<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_comment']) && isset($_SESSION['username'])) {
    if (!isset($conn)) {
        $conn = new mysqli("localhost", "root", "", "users_db");
    }
    $conn->set_charset("utf8mb4");

    $u_stmt = $conn->prepare("SELECT id FROM felhasznalok WHERE username = ?");
    $u_stmt->bind_param("s", $_SESSION['username']);
    $u_stmt->execute();
    $u_res = $u_stmt->get_result()->fetch_assoc();

    if ($u_res) {
        $user_id = $u_res['id'];
        $comment_text = htmlspecialchars($_POST['comment_text']);
        
        // Ha nem receptnél vagyunk, a recept_id legyen NULL
        $db_recept_id = ($page_type === 'recept') ? $page_id : null;

        $ins = $conn->prepare("INSERT INTO hozzaszolasok (recept_id, felhasznalo_id, szoveg, oldal_tipus) VALUES (?, ?, ?, ?)");
        $ins->bind_param("iiss", $db_recept_id, $user_id, $comment_text, $page_type);
        
        if ($ins->execute()) {
            $ins->close();
            
            // --- JAVÍTÁS: PHP header helyett JavaScript ---
            echo "<script>window.location.href='" . $_SERVER['REQUEST_URI'] . "';</script>";
            exit();
            // ----------------------------------------------
        }
    }
}

// 2. KOMMENTEK LEKÉRÉSE (ez maradhat változatlan)
if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "users_db");
    $conn->set_charset("utf8mb4");
}

// 2. KOMMENTEK LEKÉRÉSE
if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "users_db");
}
// KÖTELEZŐ: UTF-8 kódolás beállítása a lekéréshez is
$conn->set_charset("utf8mb4");

$c_stmt = $conn->prepare("
    SELECT h.szoveg, h.datum, f.username 
    FROM hozzaszolasok h 
    JOIN felhasznalok f ON h.felhasznalo_id = f.id 
    WHERE h.recept_id = ? AND h.oldal_tipus = ?
    ORDER BY h.datum DESC
");
$c_stmt->bind_param("is", $page_id, $page_type);
$c_stmt->execute();
$comments_res = $c_stmt->get_result();
?>

<div class="comment-section" style="margin-top: 50px; border-top: 1px solid #333; padding-top: 30px;">
    <h3>Vélemények (<?php echo $comments_res->num_rows; ?>)</h3>
    
    <?php if (isset($_SESSION['username'])): ?>
        <form method="POST" style="margin-top: 20px;">
            <textarea name="comment_text" required placeholder="Írd le a véleményed..." style="width:100%; height:80px; margin-bottom:10px; padding:10px; border-radius:8px; background: rgba(255,255,255,0.1); color: white; border: 1px solid #444;"></textarea>
            <button type="submit" name="send_comment" class="action-button">Küldés</button>
        </form>
    <?php else: ?>
        <p style="margin-top: 20px;">A hozzászóláshoz <a href="login.php" style="color: #5e9cff;">be kell jelentkezned</a>.</p>
    <?php endif; ?>

    <div class="comments-list" style="margin-top: 30px;">
        <?php if ($comments_res->num_rows > 0): ?>
            <?php while($c = $comments_res->fetch_assoc()): ?>
                <div class="comment-box" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding: 15px 0;">
                    <strong style="color: #5e9cff; margin-left: 5px;">
                        <?php echo htmlspecialchars($c['username']); ?>
                    </strong> 
                    <small style="color: #666; margin-left: 10px;"><?php echo $c['datum']; ?></small>
                    
                    <p style="margin-top: 10px; color: #71797E; margin-left: 15px;">
                        <?php echo nl2br(htmlspecialchars($c['szoveg'])); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #666;">Még nincs hozzászólás. Legyél te az első!</p>
        <?php endif; ?>
    </div>
</div>