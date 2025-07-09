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
    echo "<div class='shift'>";
    echo "<h2 class='shift-header'>Turno $shift_name</h2>";
    echo "<div class='shift-notes'>";
    foreach ($notes as $note) {
        if ($note['shift'] === $shift_name) {
            $title = htmlspecialchars($note['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $stateClass = 'state-' . str_replace(' ', '_', $note['state']);
            echo "<div class='task {$stateClass}'>";
            echo "<a href='note_details.php?id={$note['id']}'><strong>{$title}</strong></a> ";
            echo "[{$note['state']}] <em>{$note['date']}</em>";
            echo "</div>";
        }
    }
    echo "</div>"; // shift-notes
    echo "</div>"; // shift
}
include 'templates/footer.php';
