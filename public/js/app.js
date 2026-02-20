document.addEventListener("DOMContentLoaded", () => {


    /* ═════════════════════════════
       MENU
    ═════════════════════════════ */
    const toggle   = document.getElementById('menuToggle');
    const sidebar  = document.getElementById('sidebarMenu');
    const backdrop = document.getElementById('menuBackdrop');

    if (toggle && sidebar && backdrop) {

        const openMenu  = () => {
            toggle.classList.add('open');
            sidebar.classList.add('active');
            backdrop.classList.add('active');
        };

        const closeMenu = () => {
            toggle.classList.remove('open');
            sidebar.classList.remove('active');
            backdrop.classList.remove('active');
        };

        toggle.addEventListener('click', () =>
            toggle.classList.contains('open') ? closeMenu() : openMenu()
        );

        backdrop.addEventListener('click', closeMenu);
        sidebar.querySelectorAll('a').forEach(a =>
            a.addEventListener('click', closeMenu)
        );
    }

    /* ═════════════════════════════
       TYPEWRITER
    ═════════════════════════════ */
    const typeEl = document.getElementById('typewriterText');

    if (typeEl) {

        const phrases = [
            'Libros que cobran vida.',
            'Experiencias sin límites.',
            'Crea. Imagina. Explora.'
        ];

        let pIdx=0, cIdx=0, del=false;

        function tw() {
            const cur = phrases[pIdx];

            if (!del) {
                typeEl.textContent = cur.substring(0, ++cIdx);
                if (cIdx === cur.length) {
                    del=true;
                    setTimeout(tw, 2000);
                    return;
                }
            } else {
                typeEl.textContent = cur.substring(0, --cIdx);
                if (cIdx === 0) {
                    del=false;
                    pIdx=(pIdx+1)%phrases.length;
                }
            }

            setTimeout(tw, del?50:80);
        }

        setTimeout(tw, 1500);
    }

});