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
    $uid = $u_res['id'];

    $comment_text = trim($_POST['comment_text']);
    $current_page_type = $page_type ?? 'recept';

    if (!empty($comment_text)) {
        $ins_stmt = $conn->prepare("INSERT INTO hozzaszolasok (felhasznalo_id, recept_id, oldal_tipus, szoveg, datum) VALUES (?, ?, ?, ?, NOW())");
        $ins_stmt->bind_param("iiss", $uid, $page_id, $current_page_type, $comment_text);
        $ins_stmt->execute();
        
        echo "<script>window.location.href='recept.php?id=" . $id . "';</script>";
        exit();
    }
}

$query = "SELECT h.*, f.username, f.profile_pic 
          FROM hozzaszolasok h 
          JOIN felhasznalok f ON h.felhasznalo_id = f.id 
          WHERE h.recept_id = ? AND h.oldal_tipus = ?
          ORDER BY h.datum DESC";
$get_comments = $conn->prepare($query);
$current_page_type = $page_type ?? 'recept';
$get_comments->bind_param("is", $page_id, $current_page_type);
$get_comments->execute();
$comments_res = $get_comments->get_result();
?>

<div class="comments-section" style="margin-top: 50px; border-top: 1px solid #333; padding-top: 30px;">
    <h3 style="margin-bottom: 25px;"><i class="fas fa-comments" style="color: #5e9cff; margin-right: 10px;"></i>Kommentek (<?php echo $comments_res->num_rows; ?>)</h3>

    <?php if (isset($_SESSION['username'])): ?>
        <form method="POST" style="margin-bottom: 40px; background: #2a2a2a; padding: 20px; border-radius: 12px; border: 1px solid #333;">
            <textarea name="comment_text" placeholder="Írd le a véleményed..." required 
                      style="width: 100%; height: 80px; background: #1a1a1a; color: white; border: 1px solid #444; padding: 12px; border-radius: 8px; resize: none; font-family: inherit; font-size: 1em;"></textarea>
            <div style="text-align: right;">
                <button type="submit" name="send_comment" class="action-button" style="margin-top: 10px; padding: 10px 30px; cursor: pointer;">Küldés</button>
            </div>
        </form>
    <?php endif; ?>

    <div class="comments-list">
        <?php if ($comments_res->num_rows > 0): ?>
            <?php while ($c = $comments_res->fetch_assoc()): ?>
                <div class="comment-item" style="display: flex; gap: 15px; margin-bottom: 25px; background: #222; padding: 15px; border-radius: 12px; border: 1px solid #333;">
                    
                    <a href="profil.php?view_id=<?php echo $c['felhasznalo_id']; ?>" style="flex-shrink: 0;">
                        <img src="<?php echo htmlspecialchars(!empty($c['profile_pic']) ? $c['profile_pic'] : 'img/alap.png'); ?>" 
                             style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; margin-top: 1px; border: 2px solid #444;">
                    </a>

                    <div style="flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <a href="profil.php?view_id=<?php echo $c['felhasznalo_id']; ?>" style="text-decoration: none;">
                                <strong style="color: #5e9cff; font-size: 1.1em;"><?php echo htmlspecialchars($c['username']); ?></strong>
                            </a>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <small style="color: #666;"><?php echo date("Y.m.d. H:i", strtotime($c['datum'])); ?></small>
                                <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $c['username']): ?>
                                    <a href="recept.php?id=<?php echo $id; ?>&delete_comment_id=<?php echo $c['id']; ?>" 
                                       onclick="return confirm('Biztosan törlöd?')" 
                                       style="color: #ff6b6b; font-size: 0.9em;" title="Törlés">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p style="margin: 0; color: #ddd; line-height: 1.5; font-size: 0.95em;">
                            <?php echo nl2br(htmlspecialchars($c['szoveg'])); ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #666; text-align: center; padding: 20px;">Még nincs hozzászólás. Legyél te az első!</p>
        <?php endif; ?>
    </div>
</div>