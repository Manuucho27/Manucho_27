// El tema ya se aplicó en el script inline en head
// Agregar la clase para habilitar transiciones después de la carga inicial
document.body.classList.add('theme-loaded');


// Exponer la función de cambio de modo como global para que los `onclick` la encuentren
window.cambiaModoColor = () => {
    const isDark = document.documentElement.classList.toggle('dark-mode');
    // Guarda el estado en localStorage
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};