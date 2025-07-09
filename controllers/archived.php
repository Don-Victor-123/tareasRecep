<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}
require __DIR__ . '/../config/db.php';
$shifts = ['Matutino', 'Vespertino', 'Nocturno'];

if ($_SESSION['role'] === 'admin') {
    $stmt = $pdo->query("SELECT * FROM notes WHERE state = 'Realizada' OR date < CURDATE() ORDER BY shift, date DESC, id");
} else {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE (state = 'Realizada' OR date < CURDATE()) AND created_by = ? ORDER BY shift, date DESC, id");
    $stmt->execute([$_SESSION['user']]);
}
$notes = $stmt->fetchAll();

include __DIR__ . '/../templates/header.php';
if ($_SESSION['role'] === 'admin') {
    include __DIR__ . '/../views/admin/archived.php';
} else {
    include __DIR__ . '/../views/recepcionista/archived.php';
}
include __DIR__ . '/../templates/footer.php';
