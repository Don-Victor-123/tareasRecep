<?php
foreach ($shifts as $shift_name) {
    echo "<div class='shift'>";
    echo "<h2 class='shift-header'>Turno $shift_name</h2>";
    echo "<div class='shift-notes'>";
    foreach ($notes as $note) {
        if ($note['shift'] === $shift_name) {
            $title = htmlspecialchars($note['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $stateClass = 'state-' . str_replace(' ', '_', $note['state']);
            $desc = htmlspecialchars($note['description'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            echo "<div class='task {$stateClass}'>";
            echo "<div class='task-header'>";
            echo "<strong>{$title}</strong> [{$note['state']}] <em>{$note['date']}</em> ";
            echo "<a href='note_details.php?id={$note['id']}'>Editar</a>";
            echo "</div>";
            echo "<div class='task-desc' style='display:none'>{$desc}</div>";
            echo "</div>";
        }
    }
    echo "<form method='POST' class='new-note-form'>";
    echo "<input type='hidden' name='shift' value='{$shift_name}'>";
    echo "<input type='text' name='title' placeholder='Nueva nota' required>";
    echo "<textarea name='description' placeholder='Descripción'></textarea>";
    echo "<button type='submit'>Agregar</button>";
    echo "</form>";
    echo "</div></div>";
}
?>
