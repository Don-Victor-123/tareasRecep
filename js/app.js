// js/app.js
document.addEventListener('DOMContentLoaded', () => {
    // Aquí puedes añadir código AJAX para cambiar estados sin recargar
    document.querySelectorAll('.shift-header').forEach(header => {
        header.addEventListener('click', () => {
            const notes = header.nextElementSibling;
            notes.classList.toggle('open');
        });
    });
});
