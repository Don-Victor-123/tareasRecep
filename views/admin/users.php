<h2>Usuarios</h2>
<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<table class="users">
    <tr>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="username" value="<?= htmlspecialchars($u['username']) ?>">
                <button type="submit" onclick="return confirm('¿Eliminar usuario?');">Eliminar</button>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="username" value="<?= htmlspecialchars($u['username']) ?>">
                <input type="password" name="password" placeholder="Nueva contraseña">
                <select name="role">
                    <option value="receptionist" <?= $u['role'] === 'receptionist' ? 'selected' : '' ?>>Recepcionista</option>
                    <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <button type="submit">Guardar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
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
