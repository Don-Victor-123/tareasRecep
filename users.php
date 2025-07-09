<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
require 'config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? 'receptionist';
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, role, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $role, $hash]);
    } else {
        $error = 'Usuario y contraseña requeridos';
    }
}
$stmt = $pdo->query('SELECT username, role FROM users ORDER BY username');
$users = $stmt->fetchAll();
include 'templates/header.php';
?>
<h2>Usuarios</h2>
<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<table class="users">
    <tr><th>Usuario</th><th>Rol</th></tr>
    <?php foreach ($users as $u): ?>
    <tr><td><?= htmlspecialchars($u['username']) ?></td><td><?= htmlspecialchars($u['role']) ?></td></tr>
    <?php endforeach; ?>
</table>
<h3>Añadir usuario</h3>
<form method="POST">
    <label>Usuario: <input type="text" name="username" required></label><br>
    <label>Rol:
        <select name="role">
            <option value="receptionist">Recepcionista</option>
            <option value="admin">Admin</option>
        </select>
    </label><br>
    <label>Contraseña: <input type="password" name="password" required></label><br>
    <button type="submit">Crear</button>
</form>
<?php include 'templates/footer.php'; ?>
