/* Controlador de validación para el formulario de contacto */
import * as v from './validaciones.js';
import * as ui from './ui.js';

export function initializeContactFormController() {
    const contactoSection = document.getElementById('contacto');
    if (!contactoSection) return;
    const form = contactoSection.querySelector('form');
    if (!form) return;

    const nombre = form.querySelector('#nombre');
    const email = form.querySelector('#email');
    const mensaje = form.querySelector('#mensaje');

    if (!nombre || !email || !mensaje) return;

    nombre.addEventListener('input', function () {
        if (!v.validarNombre(nombre.value)) ui.mostrarError('error-contacto-nombre', 'Nombre inválido.', nombre);
        else ui.mostrarError('error-contacto-nombre', '', nombre);
    });

    email.addEventListener('input', function () {
        if (!v.validarCorreo(email.value)) ui.mostrarError('error-contacto-email', 'Correo inválido.', email);
        else ui.mostrarError('error-contacto-email', '', email);
    });

    mensaje.addEventListener('input', function () {
        if (!v.validarMensaje(mensaje.value)) ui.mostrarError('error-contacto-mensaje', 'Mensaje demasiado corto (mín 10 caracteres).', mensaje);
        else ui.mostrarError('error-contacto-mensaje', '', mensaje);
    });

    form.addEventListener('submit', function (e) {
        let ok = true;
        if (!v.validarNombre(nombre.value)) { ui.mostrarError('error-contacto-nombre', 'Nombre inválido.', nombre); ok = false; }
        if (!v.validarCorreo(email.value)) { ui.mostrarError('error-contacto-email', 'Correo inválido.', email); ok = false; }
        if (!v.validarMensaje(mensaje.value)) { ui.mostrarError('error-contacto-mensaje', 'Escribe un mensaje más largo.', mensaje); ok = false; }

        if (!ok) {
            e.preventDefault();
            ui.mostrarMensajeError('Corrige los errores del formulario de contacto.');
        }
    });
}
