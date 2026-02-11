/* Controlador de validación para el formulario de registro.
   Detecta el formulario de registro por la presencia de los campos esperados
   y añade validación en tiempo real y en submit.
*/
import * as v from './validaciones.js';
import * as ui from './ui.js';

export function initializeRegistroFormController() {
	const nombreInput = document.getElementById('nombre');
	const usernameInput = document.getElementById('username');
	const emailInput = document.getElementById('email');
	const passwordInput = document.getElementById('password');
	const form = document.querySelector('form[aria-label="Formulario de registro"]') || document.querySelector('form');

	if (!nombreInput || !usernameInput || !emailInput || !passwordInput || !form) return;

	nombreInput.addEventListener('input', function () {
		const ok = v.validarNombre(nombreInput.value);
		if (!ok) ui.mostrarError('error-nombre', 'Nombre inválido. Solo letras, hasta 3 palabras.', nombreInput);
		else ui.mostrarError('error-nombre', '', nombreInput);
	});

	usernameInput.addEventListener('input', function () {
		const ok = v.validarUsername(usernameInput.value);
		if (!ok) ui.mostrarError('error-username', 'Usuario inválido. 3-20 caracteres, sin espacios.', usernameInput);
		else ui.mostrarError('error-username', '', usernameInput);
	});

	emailInput.addEventListener('input', function () {
		const ok = v.validarCorreo(emailInput.value);
		if (!ok) ui.mostrarError('error-email', 'Correo con formato inválido.', emailInput);
		else ui.mostrarError('error-email', '', emailInput);
	});

	passwordInput.addEventListener('input', function () {
		const ok = v.validarContrasena(passwordInput.value);
		if (!ok) ui.mostrarError('error-password', 'Contraseña débil. Mínimo 8 caracteres, incluir mínimo una mayúscula, un número y un caracter especial.', passwordInput);
		else ui.mostrarError('error-password', '', passwordInput);
	});

	form.addEventListener('submit', function (e) {
		const nombreOk = v.validarNombre(nombreInput.value);
		const usernameOk = v.validarUsername(usernameInput.value);
		const emailOk = v.validarCorreo(emailInput.value);
		const passOk = v.validarContrasena(passwordInput.value);

		let ok = true;
		if (!nombreOk) { ui.mostrarError('error-nombre', 'Nombre inválido.', nombreInput); ok = false; }
		if (!usernameOk) { ui.mostrarError('error-username', 'Usuario inválido.', usernameInput); ok = false; }
		if (!emailOk) { ui.mostrarError('error-email', 'Correo inválido.', emailInput); ok = false; }
		if (!passOk) { ui.mostrarError('error-password', 'Contraseña no cumple requisitos.', passwordInput); ok = false; }

		if (!ok) {
			e.preventDefault();
			ui.mostrarMensajeError('Corrige los errores del formulario.');
		}
	});
}
