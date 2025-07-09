<?php
session_start();
require 'config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$user]);
    $data = $stmt->fetch();
    if ($data && password_verify($pass, $data['password'])) {
        $_SESSION['role'] = $data['role'];
        $_SESSION['user'] = $data['username'];
        header('Location: index.php');
        exit;
    }
    $error = 'Credenciales inválidas';
}
?>
<?php include 'templates/header.php'; ?>
<h2>Login</h2>
<?php if ($error): ?>
<p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST">
    <label>Usuario: <input type="text" name="user" required></label><br>
    <label>Contraseña: <input type="password" name="pass" required></label><br>
    <button type="submit">Entrar</button>
</form><?php include 'templates/footer.php'; ?>
