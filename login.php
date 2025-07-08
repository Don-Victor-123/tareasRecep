<?php
session_start();
require 'config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    // Logica simplificada: usuario 'admin' es jefe, los demas recepcionistas
    if ($user === 'admin' && $pass === 'admin') {
        $_SESSION['role'] = 'admin';
        $_SESSION['user'] = $user;
        header('Location: index.php');
    } else {
        $_SESSION['role'] = 'receptionist';
        $_SESSION['user'] = $user;
        header('Location: index.php');
    }
}
?>
<?php include 'templates/header.php'; ?>
<h2>Login</h2>
<form method="POST">
    <label>Usuario: <input type="text" name="user" required></label><br>
    <label>Contraseña: <input type="password" name="pass" required></label><br>
    <button type="submit">Entrar</button>
</form>
<?php include 'templates/footer.php'; ?>