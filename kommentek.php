<?php
if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "users_db");
    $conn->set_charset("utf8mb4");
}

if (isset($_GET['delete_comment_id']) && isset($_SESSION['username'])) {
    $del_com_id = intval($_GET['delete_comment_id']);
    
    $u_check = $conn->prepare("SELECT id FROM felhasznalok WHERE username = ?");
    $u_check->bind_param("s", $_SESSION['username']);
    $u_check->execute();
    $uid_res = $u_check->get_result()->fetch_assoc();
    $uid = $uid_res['id'];

    $del_stmt = $conn->prepare("DELETE FROM hozzaszolasok WHERE id = ? AND felhasznalo_id = ?");
    $del_stmt->bind_param("ii", $del_com_id, $uid);
    $del_stmt->execute();
    
    echo "<script>window.location.href='recept.php?id=" . $id . "';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_comment']) && isset($_SESSION['username'])) {
    $u_stmt = $conn->prepare("SELECT id FROM felhasznalok WHERE username = ?");
    $u_stmt->bind_param("s", $_SESSION['username']);
    $u_stmt->execute();
    $u_res = $u_stmt->get_result()->fetch_assoc();

    if ($u_res) {
        $user_id = $u_res['id'];
        $comment_text = trim($_POST['comment_text']);
        
        if (!empty($comment_text)) {
            $db_recept_id = ($page_type === 'recept') ? $page_id : null;
            $ins = $conn->prepare("INSERT INTO hozzaszolasok (recept_id, felhasznalo_id, szoveg, oldal_tipus) VALUES (?, ?, ?, ?)");
            $ins->bind_param("iiss", $db_recept_id, $user_id, $comment_text, $page_type);
            
            if ($ins->execute()) {
                echo "<script>window.location.href='" . $_SERVER['REQUEST_URI'] . "';</script>";
                exit();
            }
        }
    }
}

$sql = "SELECT h.*, f.username, f.profile_pic, f.id as user_id 
        FROM hozzaszolasok h 
        JOIN felhasznalok f ON h.felhasznalo_id = f.id 
        WHERE h.recept_id = ? 
        ORDER BY h.datum DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$comments_res = $stmt->get_result();
?>

<div class="comments-section" style="margin-top: 50px; border-top: 1px solid #444; padding-top: 20px;">
    <h3>Vélemények</h3>

    <?php if (isset($_SESSION['username'])): ?>
        <form method="POST" style="margin-top: 20px;">
            <textarea name="comment_text" required placeholder="Írd le a véleményed..." style="width:100%; height:80px; margin-bottom:10px; padding:10px; border-radius:8px; background: rgba(255,255,255,0.1); color: white; border: 1px solid #444; font-family: inherit; resize: vertical;"></textarea>
            <button type="submit" name="send_comment" class="action-button">Küldés</button>
        </form>
    <?php else: ?>
        <p style="margin-top: 20px;">A hozzászóláshoz <a href="login.php" style="color: #5e9cff;">be kell jelentkezned</a>.</p>
    <?php endif; ?>

    <div class="comments-list" style="margin-top: 30px;">
        <?php if ($comments_res && $comments_res->num_rows > 0): ?>
            <?php while($c = $comments_res->fetch_assoc()): ?>
                
                <div class="comment-box" style="display: flex; gap: 15px; align-items: flex-start; background: #2a2a2a; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #333; border-left: none;">
                    
                    <a href="profil.php?view_id=<?php echo $c['user_id']; ?>" style="flex-shrink: 0;">
                        <img src="<?php echo htmlspecialchars(!empty($c['profile_pic']) ? $c['profile_pic'] : 'img/alap.png'); ?>" 
                             alt="<?php echo htmlspecialchars($c['username']); ?>"
                             style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #444;">
                    </a>

                    <div style="flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                            <a href="profil.php?view_id=<?php echo $c['user_id']; ?>" style="text-decoration: none;">
                                <strong style="color: #5e9cff; font-size: 1.05em;"><?php echo htmlspecialchars($c['username']); ?></strong>
                            </a>
                            
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <small style="color: #666;"><?php echo $c['datum']; ?></small>
                                
                                <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $c['username']): ?>
                                    <a href="recept.php?id=<?php echo $id; ?>&delete_comment_id=<?php echo $c['id']; ?>" 
                                       onclick="return confirm('Törlöd a hozzászólást?')" 
                                       title="Törlés"
                                       style="color: #e74c3c; text-decoration: none; font-size: 0.9em; line-height: 1;">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <p style="margin: 0; color: #ddd; line-height: 1.4;"><?php echo nl2br(htmlspecialchars($c['szoveg'])); ?></p>
                    </div>

                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #666; margin-top: 20px;">Még nincs hozzászólás. Legyél te az első!</p>
        <?php endif; ?>
    </div>
</div>