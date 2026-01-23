/**
 * Función para comprobar si el modo oscuro está activado
 * @return {boolean} true si el modo oscuro está activado, false en caso contrario.
 */
export function esModoOscuro() {
    // Primero, verifica si hay una preferencia guardada en localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        return savedTheme === 'dark';
    }
    // Si no hay guardado, usa la preferencia del sistema
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
    return prefersDark.matches;
}
