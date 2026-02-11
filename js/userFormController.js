import * as v from './validaciones.js';
import * as ui from './ui.js';

export function initializeUserFormController() {
    const form = document.querySelector('form[data-user-form]') || document.querySelector('form.user-form');
    if (!form) return;

    const nombre = form.querySelector('#nombre');
    const username = form.querySelector('#username');
    const email = form.querySelector('#email');
    const password = form.querySelector('#password');

    if (nombre) {
        nombre.addEventListener('input', () => {
            if (!v.validarNombre(nombre.value)) ui.mostrarError('error-nombre', 'Nombre inválido.', nombre);
            else ui.mostrarError('error-nombre', '', nombre);
        });
    }

    if (username) {
        username.addEventListener('input', () => {
            if (!v.validarUsername(username.value)) ui.mostrarError('error-username', 'Usuario inválido.', username);
            else ui.mostrarError('error-username', '', username);
        });
    }

    if (email) {
        email.addEventListener('input', () => {
            if (!v.validarCorreo(email.value)) ui.mostrarError('error-email', 'Correo inválido.', email);
            else ui.mostrarError('error-email', '', email);
        });
    }

    if (password) {
        password.addEventListener('input', () => {
            if (!v.validarContrasena(password.value)) ui.mostrarError('error-password', 'Contraseña débil.', password);
            else ui.mostrarError('error-password', '', password);
        });
    }

    form.addEventListener('submit', (e) => {
        let ok = true;
        if (nombre && !v.validarNombre(nombre.value)) { ui.mostrarError('error-nombre', 'Nombre inválido.', nombre); ok = false; }
        if (username && !v.validarUsername(username.value)) { ui.mostrarError('error-username', 'Usuario inválido.', username); ok = false; }
        if (email && !v.validarCorreo(email.value)) { ui.mostrarError('error-email', 'Correo inválido.', email); ok = false; }
        if (password && !v.validarContrasena(password.value)) { ui.mostrarError('error-password', 'Contraseña inválida.', password); ok = false; }

        if (!ok) {
            e.preventDefault();
            ui.mostrarMensajeError('Corrige los errores del formulario.');
        }
    });
}
