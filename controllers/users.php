<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}
$isAdmin = $_SESSION['role'] === 'admin';
require __DIR__ . '/../config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'receptionist';
    if ($action === 'create' && $isAdmin) {
        if ($username && $password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, role, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $role, $hash]);
        } else {
            $error = 'Usuario y contraseña requeridos';
        }
    } elseif ($action === 'update' && ($isAdmin || $username === $_SESSION['user'])) {
        $fields = [];
        $params = [];
        if ($password !== '') {
            $fields[] = 'password = ?';
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($isAdmin) {
            $fields[] = 'role = ?';
            $params[] = $role;
        }
        if ($fields) {
            $params[] = $username;
            $stmt = $pdo->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE username = ?');
            $stmt->execute($params);
        }
    } elseif ($action === 'delete' && $isAdmin) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE username = ?');
        $stmt->execute([$username]);
    }
}

$stmt = $pdo->prepare($isAdmin ? 'SELECT username, role FROM users ORDER BY username' : 'SELECT username, role FROM users WHERE username = ?');
if ($isAdmin) {
    $stmt->execute();
} else {
    $stmt->execute([$_SESSION['user']]);
}
$users = $stmt->fetchAll();

include __DIR__ . '/../templates/header.php';
if ($isAdmin) {
    include __DIR__ . '/../views/admin/users.php';
} else {
    include __DIR__ . '/../views/recepcionista/users.php';
}
include __DIR__ . '/../templates/footer.php';
