<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}
require __DIR__ . '/../config/db.php';
$id = $_GET['id'] ?? null;
if (!$id) die('Nota no encontrada');

$stmt = $pdo->prepare('SELECT * FROM notes WHERE id = ?');
$stmt->execute([$id]);
$note = $stmt->fetch();
if (!$note) die('Nota no encontrada');

if ($_SESSION['role'] !== 'admin' && $note['created_by'] !== $_SESSION['user']) {
    die('Acceso denegado');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $state = $_POST['state'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare('UPDATE notes SET state = ?, description = ? WHERE id = ?');
    $stmt->execute([$state, $description, $id]);
    if ($state === 'Realizada') {
        header('Location: ../archived.php');
    } else {
        header("Location: ../note_details.php?id=$id");
    }
    exit;
}

include __DIR__ . '/../templates/header.php';
if ($_SESSION['role'] === 'admin') {
    include __DIR__ . '/../views/admin/note_details.php';
} else {
    include __DIR__ . '/../views/recepcionista/note_details.php';
}
include __DIR__ . '/../templates/footer.php';
