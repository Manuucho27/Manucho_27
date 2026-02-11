/* UI helpers globales para controladores no-module */
// UI helpers exportados como módulo
export function esModoOscuro() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) return savedTheme === 'dark';
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
    return prefersDark.matches;
}

function _createErrorElementIfMissing(forElement, id) {
    if (!document.getElementById(id)) {
        const span = document.createElement('div');
        span.id = id;
        span.className = 'error small text-danger';
        span.setAttribute('aria-live', 'polite');
        if (forElement && forElement.parentNode) {
            const parent = forElement.parentNode;
            parent.appendChild(span);
        } else {
            document.body.appendChild(span);
        }
    }
    return document.getElementById(id);
}

export function mostrarError(elementoId, mensaje, forElement) {
    const el = document.getElementById(elementoId) || _createErrorElementIfMissing(forElement, elementoId);
    if (el) el.textContent = mensaje;
}

export function limpiarFormulario(form) {
    if (!form) form = document.querySelector('form');
    if (form) form.reset();
}

export function ocultarErrores() {
    const errores = document.querySelectorAll('.error');
    errores.forEach(error => { error.textContent = ''; });
}

export function mostrarMensajeExito(mensaje) { alert(mensaje); }
export function mostrarMensajeError(mensaje) { alert('Error: ' + mensaje); }

export default {
    esModoOscuro,
    mostrarError,
    limpiarFormulario,
    ocultarErrores,
    mostrarMensajeExito,
    mostrarMensajeError
};
