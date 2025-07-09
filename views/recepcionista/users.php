<h2>Mi cuenta</h2>
<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<table class="users">
    <tr>
        <th>Usuario</th>
        <th>Rol</th>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="username" value="<?= htmlspecialchars($u['username']) ?>">
                <input type="password" name="password" placeholder="Nueva contraseña">
                <button type="submit">Guardar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
