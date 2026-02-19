import './bootstrap';

// Menú Toggle
function initMenu() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const menuClose = document.getElementById('menuClose');

    // Abrir menú
    menuToggle.addEventListener('click', () => {
        sidebarMenu.classList.add('active');
    });

    // Cerrar menú con el botón X
    menuClose.addEventListener('click', () => {
        sidebarMenu.classList.remove('active');
    });

    // Cerrar menú al hacer clic en un enlace
    const menuLinks = sidebarMenu.querySelectorAll('a');
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            sidebarMenu.classList.remove('active');
        });
    });

    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!sidebarMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebarMenu.classList.remove('active');
        }
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initMenu);
