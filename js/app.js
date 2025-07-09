// js/app.js
document.addEventListener('DOMContentLoaded', () => {
    // Aquí puedes añadir código AJAX para cambiar estados sin recargar
    document.querySelectorAll('.shift-header').forEach(header => {
        header.addEventListener('click', () => {
            const notes = header.nextElementSibling;
            notes.classList.toggle('open');
        });
    });
    document.querySelectorAll('.task-header').forEach(th => {
        th.addEventListener('click', () => {
            const desc = th.nextElementSibling;
            if (desc) desc.style.display = desc.style.display === 'none' ? 'block' : 'none';
        });
    });
});
