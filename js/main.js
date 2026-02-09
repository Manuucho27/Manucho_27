// El tema ya se aplicó en el script inline en head
// Agregar la clase para habilitar transiciones después de la carga inicial
document.body.classList.add('theme-loaded');


// Exponer la función de cambio de modo como global para que los `onclick` la encuentren
window.cambiaModoColor = () => {
    const isDark = document.documentElement.classList.toggle('dark-mode');
    // Guarda el estado en localStorage
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

// Accesibilidad: aumentar/disminuir texto y alto contraste
(function() {
    const ROOT = document.documentElement;
    const STORAGE_KEY = 'accessibility';

    function getSettings() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; } catch (e) { return {}; }
    }

    function saveSettings(s) { localStorage.setItem(STORAGE_KEY, JSON.stringify(s)); }

    function applySettings() {
        const s = getSettings();
        if (s.fontScale) {
            ROOT.style.fontSize = s.fontScale + 'px';
        }
        if (s.highContrast) {
            ROOT.classList.add('high-contrast');
        } else {
            ROOT.classList.remove('high-contrast');
        }
    }

    function changeFont(delta) {
        const current = parseFloat(getComputedStyle(ROOT).fontSize) || 16;
        const next = Math.max(12, Math.min(24, Math.round(current + delta)));
        const s = getSettings();
        s.fontScale = next;
        saveSettings(s);
        applySettings();
    }

    function setHighContrast(on) {
        const s = getSettings();
        s.highContrast = !!on;
        saveSettings(s);
        applySettings();
    }

    document.addEventListener('DOMContentLoaded', function() {
        applySettings();

        const inc = document.getElementById('aumentar-texto');
        const dec = document.getElementById('disminuir-texto');
        const hcOn = document.getElementById('alto-contraste');
        const hcOff = document.getElementById('contraste-normal');

        if (inc) inc.addEventListener('click', function(e){ e.preventDefault(); changeFont(2); });
        if (dec) dec.addEventListener('click', function(e){ e.preventDefault(); changeFont(-2); });
        if (hcOn) hcOn.addEventListener('click', function(e){ e.preventDefault(); setHighContrast(true); });
        if (hcOff) hcOff.addEventListener('click', function(e){ e.preventDefault(); setHighContrast(false); });
    });

    // Export for debugging/tests
    window.__accessibility = { changeFont, setHighContrast, applySettings };
})();

// Custom cursor logo: muestra el logo en la esquina inferior derecha del puntero
(function() {
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) return; // no en pantallas táctiles

    const logo = document.createElement('img');
    logo.id = 'cursor-logo';
    logo.src = '/Manucho27/img/iconos/favicon.png';
    logo.alt = 'logo';
    logo.style.position = 'fixed';
    logo.style.width = '28px';
    logo.style.height = '28px';
    logo.style.pointerEvents = 'none';
    logo.style.zIndex = 999999;
    logo.style.transition = 'transform .08s linear, opacity .12s linear';
    logo.style.opacity = '0.95';
    document.body.appendChild(logo);

    const offset = 10; // distancia desde el puntero hacia abajo-derecha

    function move(e) {
        const x = e.clientX + offset;
        const y = e.clientY + offset;
        logo.style.left = x + 'px';
        logo.style.top = y + 'px';
    }

    document.addEventListener('mousemove', move);
    document.addEventListener('mouseleave', function(){ logo.style.opacity = '0'; });
    document.addEventListener('mouseenter', function(){ logo.style.opacity = '0.95'; });
})();