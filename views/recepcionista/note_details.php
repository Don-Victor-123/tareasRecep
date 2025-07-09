<?php
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
</form>
