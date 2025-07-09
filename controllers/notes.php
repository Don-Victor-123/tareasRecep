<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}
require __DIR__ . '/../config/db.php';
$shifts = ['Matutino', 'Vespertino', 'Nocturno'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $shift = $_POST['shift'] ?? '';
    if ($title && in_array($shift, $shifts, true)) {
        $stmt = $pdo->prepare("INSERT INTO notes (title, description, state, date, shift, created_by) VALUES (?, ?, 'Pendiente', CURDATE(), ?, ?)");
        $stmt->execute([$title, $description, $shift, $_SESSION['user']]);
    }
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['role'] === 'admin') {
    $stmt = $pdo->query("SELECT * FROM notes WHERE date = CURDATE() AND state != 'Realizada' ORDER BY shift, id");
} else {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE date = CURDATE() AND state != 'Realizada' AND created_by = ? ORDER BY shift, id");
    $stmt->execute([$_SESSION['user']]);
}
$notes = $stmt->fetchAll();

include __DIR__ . '/../templates/header.php';
if ($_SESSION['role'] === 'admin') {
    include __DIR__ . '/../views/admin/notes.php';
} else {
    include __DIR__ . '/../views/recepcionista/notes.php';
}
include __DIR__ . '/../templates/footer.php';
