import * as ui from './ui.js';

function validarImagenFile(file) {
    if (!file) return false;
    const allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
    const maxSize = 3 * 1024 * 1024; // 3MB
    return allowed.includes(file.type) && file.size <= maxSize;
}

export function initializeSubirFicheroFormController() {
    // Formulario de admin: detectar por input#imagen
    const fileInput = document.getElementById('imagen');
    if (!fileInput) return;

    const form = fileInput.closest('form');
    if (!form) return;

    const nombre = form.querySelector('#nombre');
    const precio = form.querySelector('#precio');
    const stock = form.querySelector('#stock');

    fileInput.addEventListener('change', function () {
        const f = fileInput.files[0];
        if (!validarImagenFile(f)) ui.mostrarError('error-imagen', 'Imagen inválida (png/jpg/webp, <=3MB).', fileInput);
        else ui.mostrarError('error-imagen', '', fileInput);
    });

    form.addEventListener('submit', function (e) {
        let ok = true;
        if (nombre && String(nombre.value).trim().length < 2) { ui.mostrarError('error-nombre', 'Nombre demasiado corto.', nombre); ok = false; }
        if (precio) {
            const val = parseFloat(precio.value);
            if (Number.isNaN(val) || val < 0) { ui.mostrarError('error-precio', 'Precio inválido.', precio); ok = false; }
        }
        if (stock) {
            const val = parseInt(stock.value, 10);
            if (Number.isNaN(val) || val < 0) { ui.mostrarError('error-stock', 'Stock inválido.', stock); ok = false; }
        }
        const f = fileInput.files[0];
        if (!validarImagenFile(f)) { ui.mostrarError('error-imagen', 'Imagen inválida (png/jpg/webp, <=3MB).', fileInput); ok = false; }

        if (!ok) {
            e.preventDefault();
            ui.mostrarMensajeError('Corrige los errores del formulario de subida.');
        }
    });
}
