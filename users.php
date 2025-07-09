<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$isAdmin = $_SESSION['role'] === 'admin';
require 'config/db.php';
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
include 'templates/header.php';
?>
<h2>Usuarios</h2>
<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<table class="users">
    <tr>
        <th>Usuario</th>
        <th>Rol</th>
        <?php if ($isAdmin): ?><th>Acciones</th><?php endif; ?>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <?php if ($isAdmin): ?>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="username" value="<?= htmlspecialchars($u['username']) ?>">
                <button type="submit" onclick="return confirm('¿Eliminar usuario?');">Eliminar</button>
            </form>
        </td>
        <?php endif; ?>
    </tr>
    <tr>
        <td colspan="<?= $isAdmin ? 3 : 2 ?>">
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="username" value="<?= htmlspecialchars($u['username']) ?>">
                <input type="password" name="password" placeholder="Nueva contraseña">
                <?php if ($isAdmin): ?>
                    <select name="role">
                        <option value="receptionist" <?= $u['role'] === 'receptionist' ? 'selected' : '' ?>>Recepcionista</option>
                        <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                <?php endif; ?>
                <button type="submit">Guardar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php if ($isAdmin): ?>
<h3>Añadir usuario</h3>
<form method="POST">
    <input type="hidden" name="action" value="create">
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
<?php endif; ?>
<?php include 'templates/footer.php'; ?>
