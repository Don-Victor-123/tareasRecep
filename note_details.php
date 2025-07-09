<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';
$id = $_GET['id'] ?? null;
if (!$id) die('Nota no encontrada');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar estado y detalles
    $state = $_POST['state'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("UPDATE notes SET state = ?, description = ? WHERE id = ?");
    $stmt->execute([$state, $description, $id]);
    if ($state === 'Realizada') {
        header('Location: archived.php');
    } else {
        header("Location: note_details.php?id=$id");
    }
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->execute([$id]);
$note = $stmt->fetch();
include 'templates/header.php';
$title = htmlspecialchars($note['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo "<h2>{$title}</h2>";
?>
<form method="POST">
    <label>Estado:
        <select name="state">
            <option value="Pendiente" <?= $note['state'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="En proceso" <?= $note['state'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
            <option value="Realizada" <?= $note['state'] === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
        </select>
    </label><br>
    <label>Descripción:<br>
        <textarea name="description" rows="5"><?= htmlspecialchars($note['description']) ?></textarea>
    </label><br>
    <button type="submit">Guardar</button>
</form><?php include 'templates/footer.php'; ?>
