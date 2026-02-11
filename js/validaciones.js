// Validaciones globales accesibles desde controladores no-module
// Validaciones exportadas como módulo
/* eslint-disable no-unused-vars */
export function validarNombre(nombre) {
    if (!nombre) return false;
    const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü]+(?: [A-Za-zÁÉÍÓÚáéíóúÑñÜü]+){0,2}$/;
    return regex.test(String(nombre).trim());
}

export function validarUsername(username) {
    if (!username) return false;
    const s = String(username).trim();
    // No permitir ñ ni letras con tilde/dieresis
    const forbidden = /[\u00C0-\u017FñÑ]/;
    if (forbidden.test(s)) return false;
    // Permitir letras ASCII, números y _ - . entre 3 y 40 caracteres
    const regex = /^[A-Za-z0-9_.\-]{3,40}$/;
    return regex.test(s);
}

export function validarCorreo(correo) {
    if (!correo) return false;
    const s = String(correo).trim();
    // No permitir caracteres no-ascii de tipo ñ o letras acentuadas en ninguna parte
    const forbiddenChars = /[\u00C0-\u017F]/; // incluye letras con tilde y dieresis
    if (forbiddenChars.test(s)) return false;

    // Debe tener exactamente una @
    const parts = s.split('@');
    if (parts.length !== 2) return false;
    const [local, domain] = parts;
    if (!local || !domain) return false;

    // Local part: permitir letras ASCII, números y estos símbolos: !#$%&'*+/=?^_`{|}~-. (puntos permitidos)
    if (local.startsWith('.') || local.endsWith('.') || local.includes('..')) return false;
    const localAllowed = /^[A-Za-z0-9!#$%&'*+\/=\?\^_`\{\|\}~.\-]+$/;
    if (!localAllowed.test(local)) return false;
    // Validación de longitud: ignorar puntos para el recuento
    const localCharsNoDots = local.replace(/\./g, '');
    if (localCharsNoDots.length < 3) return false;

    // Domain part: no permitir números en el dominio
    const domainAllowed = /^[A-Za-z\-]+(?:\.[A-Za-z\-]+)*$/;
    if (!domainAllowed.test(domain)) return false;
    const domainCharsNoDots = domain.replace(/\./g, '');
    if (domainCharsNoDots.length < 3) return false;

    return true;
}

export function validarContrasena(contrasena) {
    if (!contrasena) return false;
    const s = String(contrasena);
    // No permitir ñ ni letras con tilde/dieresis
    const forbidden = /[\u00C0-\u017FñÑ]/;
    if (forbidden.test(s)) return false;
    // Requerir min 8, una mayúscula, una minúscula, un dígito y un carácter especial
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
    return regex.test(s);
}

export function comprobarContrasenas(c1, c2) {
    return String(c1) === String(c2);
}

export function validarMensaje(mensaje) {
    if (!mensaje) return false;
    const len = String(mensaje).trim().length;
    return len >= 10 && len <= 2000;
}

export function validarLoginField(login) {
    if (!login) return false;
    const s = String(login).trim();
    // Login puede ser email o username. username usa mismas reglas que validarUsername
    return validarCorreo(s) || /^[A-Za-z0-9_.\-]{3,40}$/.test(s);
}

export default {
    validarNombre,
    validarUsername,
    validarCorreo,
    validarContrasena,
    comprobarContrasenas,
    validarMensaje,
    validarLoginField
};