<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';
include 'templates/header.php';
echo '<h2>Notas Archivadas</h2>';
$stmt = $pdo->prepare("SELECT * FROM notes WHERE date < CURDATE() ORDER BY date DESC");
$stmt->execute();
$notes = $stmt->fetchAll();
foreach ($notes as $note) {
    echo "<div class='task'>";
    echo "<a href='note_details.php?id={$note['id']}'><strong>{$note['title']}</strong></a> ";
    echo "[{$note['state']}] <em>{$note['date']}</em>";
    echo "</div>";
}
include 'templates/footer.php'; ?>