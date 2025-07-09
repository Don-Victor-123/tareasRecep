<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';
include 'templates/header.php';

// Obtener notas del día actual
$stmt = $pdo->prepare("SELECT * FROM notes WHERE date = CURDATE() ORDER BY shift, id");
$stmt->execute();
$notes = $stmt->fetchAll();
$shifts = ['Matutino', 'Vespertino', 'Nocturno'];
foreach ($shifts as $shift_name) {
    echo "<h2>Turno $shift_name</h2>";
    foreach ($notes as $note) {
        if ($note['shift'] === $shift_name) {
            $title = htmlspecialchars($note['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            echo "<div class='task'>";
            echo "<a href='note_details.php?id={$note['id']}'><strong>{$title}</strong></a> ";
            echo "[{$note['state']}] <em>{$note['date']}</em>";
            echo "</div>";
        }
    }
}include 'templates/footer.php';
