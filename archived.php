<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';
$shifts = ['Matutino', 'Vespertino', 'Nocturno'];
include 'templates/header.php';
echo '<h2>Notas Archivadas</h2>';
$stmt = $pdo->prepare("SELECT * FROM notes WHERE state = 'Realizada' OR date < CURDATE() ORDER BY shift, date DESC, id");
$stmt->execute();
$notes = $stmt->fetchAll();
foreach ($shifts as $shift_name) {
    echo "<div class='shift'>";
    echo "<h2 class='shift-header'>Turno $shift_name</h2>";
    echo "<div class='shift-notes'>";
    foreach ($notes as $note) {
        if ($note['shift'] === $shift_name) {
            $title = htmlspecialchars($note['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $stateClass = 'state-' . str_replace(' ', '_', $note['state']);
            $desc = htmlspecialchars($note['description'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            echo "<div class='task {$stateClass}'>";
            echo "<div class='task-header'>";
            echo "<strong>{$title}</strong> [{$note['state']}] <em>{$note['date']}</em> ";
            echo "<a href='note_details.php?id={$note['id']}'>Editar</a>";
            echo "</div>";
            echo "<div class='task-desc' style='display:none'>{$desc}</div>";
            echo "</div>";
        }
    }
    echo "</div></div>";
}
include 'templates/footer.php'; ?>
