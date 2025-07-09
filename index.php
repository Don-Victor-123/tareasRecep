<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';
$shifts = ['Matutino', 'Vespertino', 'Nocturno'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $shift = $_POST['shift'] ?? '';
    if ($title && in_array($shift, $shifts, true)) {
        $stmt = $pdo->prepare("INSERT INTO notes (title, description, state, date, shift, created_by) VALUES (?, ?, 'Pendiente', CURDATE(), ?, ?)");
        $stmt->execute([$title, $description, $shift, $_SESSION['user']]);
    }
    header('Location: index.php');
    exit;
}

include 'templates/header.php';

// Obtener notas del día actual
$stmt = $pdo->prepare("SELECT * FROM notes WHERE date = CURDATE() ORDER BY shift, id");
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
    echo "<form method='POST' class='new-note-form'>";
    echo "<input type='hidden' name='shift' value='{$shift_name}'>";
    echo "<input type='text' name='title' placeholder='Nueva nota' required>";
    echo "<textarea name='description' placeholder='Descripción'></textarea>";
    echo "<button type='submit'>Agregar</button>";
    echo "</form>";
    echo "</div>"; // shift-notes
    echo "</div>"; // shift
}
include 'templates/footer.php';
