import * as ui from './ui.js';

export function initializeActualizarFicheroFormController() {
    // Formulario de editar producto: busca campos con sufijo _ed
    const nombreEd = document.getElementById('nombre_ed');
    const precioEd = document.getElementById('precio_ed');
    const stockEd = document.getElementById('stock_ed');
    const form = (nombreEd && nombreEd.closest('form')) || document.querySelector('form');
    if (!form) return;

    if (precioEd) {
        precioEd.addEventListener('input', function () {
            const val = parseFloat(precioEd.value);
            if (Number.isNaN(val) || val < 0) ui.mostrarError('error-precio-ed', 'Precio inválido.', precioEd);
            else ui.mostrarError('error-precio-ed', '', precioEd);
        });
    }

    if (stockEd) {
        stockEd.addEventListener('input', function () {
            const val = parseInt(stockEd.value, 10);
            if (Number.isNaN(val) || val < 0) ui.mostrarError('error-stock-ed', 'Stock inválido.', stockEd);
            else ui.mostrarError('error-stock-ed', '', stockEd);
        });
    }

    form.addEventListener('submit', function (e) {
        let ok = true;
        if (nombreEd && String(nombreEd.value).trim().length < 2) { ui.mostrarError('error-nombre-ed', 'Nombre demasiado corto.', nombreEd); ok = false; }
        if (precioEd) {
            const val = parseFloat(precioEd.value);
            if (Number.isNaN(val) || val < 0) { ui.mostrarError('error-precio-ed', 'Precio inválido.', precioEd); ok = false; }
        }
        if (stockEd) {
            const val = parseInt(stockEd.value, 10);
            if (Number.isNaN(val) || val < 0) { ui.mostrarError('error-stock-ed', 'Stock inválido.', stockEd); ok = false; }
        }

        if (!ok) {
            e.preventDefault();
            ui.mostrarMensajeError('Corrige los errores del formulario de edición.');
        }
    });
}
