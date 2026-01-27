<?php
session_start();
// Kapcsolódás az adatbázishoz
$conn = new mysqli("localhost", "root", "", "users_db");

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// --- JAVÍTÁS: Ékezetek biztosítása a lekéréseknél ---
$conn->set_charset("utf8mb4");
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konyhai Fogalmak</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

<?php include 'header.php'; ?>

<div class="content">
    <h1 id="pageTitle" style="margin-bottom: 20px;">Konyhai Fogalmak</h1>
    
    <table class="helper-table">
        <thead>
            <tr>
                <th>Művelet</th>
                <th>Leírás</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Abálás</td><td>Forráspont alatti (kb. 95 °C-os) hőkezelés. Leggyakrabban szalonnát, hurkaféléket abálunk...</td></tr>
            <tr><td>Al dente</td><td>„Fogkeményre” főzött állag, főként tésztákra használjuk.</td></tr>
            <tr><td>Angolos-ra (rare) sütés</td><td>Olyan hússütési mód, ahol csak a hús külső része sül át, belül nyers marad.</td></tr>
            <tr><td>Aszpik</td><td>Zselésítő anyag (csontból, bőrből vagy zselatinból).</td></tr>
            <tr><td>Bardírozás</td><td>Húsok szalonnaszeletekkel való beborítása sütés előtt a kiszáradás ellen.</td></tr>
            <tr><td>Bécsi bundázás</td><td>Liszt → tojás → zsemlemorzsa, majd sütés.</td></tr>
            <tr><td>Blansírozás</td><td>Rövid forrázás, majd lehűtés fertőtlenítésre vagy előkészítésre.</td></tr>
            <tr><td>Bő zsiradékban sütés</td><td>Az alapanyagot teljesen ellepi a forró zsiradék (pl. rántott hús).</td></tr>
            <tr><td>Buggyantás</td><td>Héj nélküli tojás készítése enyhén ecetes, gyöngyöző vízben.</td></tr>
            <tr><td>Csőben sütés</td><td>Előfőzött étel sütőben való rápirítása (gratinírozás).</td></tr>
            <tr><td>Darabolás</td><td>Alapanyagok méretre vágása az egyenletes hőkezeléshez.</td></tr>
            <tr><td>Derítés</td><td>Levesek tisztítása tojásfehérjével a kristálytiszta léért.</td></tr>
            <tr><td>Elősütés</td><td>Hús gyors átsütése kevés zsiradékban pörzsréteg képzésére.</td></tr>
            <tr><td>Fehér rántás</td><td>Nem pirított liszt és zsiradék keveréke világos mártásokhoz.</td></tr>
            <tr><td>Fényezés</td><td>Felületek fényessé tétele vajjal, zsírral vagy aszpikkal.</td></tr>
            <tr><td>Filé</td><td>Csont nélküli, letisztított húsrész.</td></tr>
            <tr><td>Flambírozás</td><td>Étel leöntése alkohollal és meggyújtása az aroma megőrzéséért.</td></tr>
            <tr><td>Gőzölés</td><td>Kíméletes puhítás csak forró gőz használatával.</td></tr>
            <tr><td>Gratinírozás</td><td>Pirított felső réteg kialakítása sütőben.</td></tr>
            <tr><td>Gyors sűrítés</td><td>Liszttel elkevert vaj hozzáadása forrásban lévő ételhez.</td></tr>
            <tr><td>Habarás</td><td>Liszt tejtermékkel elkeverve sűrítés céljából.</td></tr>
            <tr><td>Juliennere vágás</td><td>Gyufaszál vastagságú, hosszúkás darabolási forma.</td></tr>
            <tr><td>Kiforralás</td><td>Liszt ízének eltüntetése további forralással.</td></tr>
            <tr><td>Klopfolás</td><td>Hússzeletek rostjainak lazítása húsverővel.</td></tr>
            <tr><td>Montírozás</td><td>Vaj hozzákeverése kész ételhez az állag javítására (forralás nélkül).</td></tr>
            <tr><td>Passzírozás</td><td>Főtt alapanyag áttörése szitán a magok/héj eltávolítására.</td></tr>
            <tr><td>Párolás</td><td>Kevés zsiradékon pirítás, majd fedő alatt, kevés lében puhítás.</td></tr>
            <tr><td>Rántás</td><td>Liszt és zsiradék megpirítása, majd folyadékkal felöntése.</td></tr>
            <tr><td>Smizírozás</td><td>Formák belső falának bevonása aszpikkal díszítéshez.</td></tr>
            <tr><td>Világos rántás</td><td>Enyhén pirított lisztből készült sűrítőanyag.</td></tr>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>
<?php 
$conn->close(); 
?>