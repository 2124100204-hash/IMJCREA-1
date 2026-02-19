

/* =======================================================
   INICIALIZACIÓN GENERAL
======================================================= */
document.addEventListener("DOMContentLoaded", () => {
    initDecryptTitle();
    initTypedSlogan();
    initGalleryTilt();
    initMenu();
  
});


/* =======================================================
   1️⃣ EFECTO DECODIFICACIÓN TÍTULO
======================================================= */
function initDecryptTitle() {
    const titleEl = document.getElementById('decrypt-title');
    if (!titleEl) return;

    const original = "INMERSIA";
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+";

    titleEl.addEventListener("mouseover", () => {
        let iter = 0;
        const inv = setInterval(() => {
            titleEl.innerText = original.split("").map((l, i) => {
                if (i < iter) return original[i];
                return letters[Math.floor(Math.random() * letters.length)];
            }).join("");

            if (iter >= original.length) clearInterval(inv);
            iter += 1 / 3;
        }, 30);
    });
}


/* =======================================================
   2️⃣ EFECTO TYPEWRITER SLOGAN
======================================================= */
function initTypedSlogan() {
    const sloganEl = document.getElementById('typed-slogan');
    if (!sloganEl) return;

    const phrases = [
        "Inmersión real en un mundo irreal",
        "Innovación Digital",
        "Diseño Inmersivo"
    ];

    let phraseIdx = 0;
    let charIndex = 0;
    let isDeleting = false;

    function type() {
        const current = phrases[phraseIdx];

        if (isDeleting) {
            sloganEl.textContent = current.substring(0, charIndex - 1);
            charIndex--;
        } else {
            sloganEl.textContent = current.substring(0, charIndex + 1);
            charIndex++;
        }

        let speed = isDeleting ? 50 : 100;

        if (!isDeleting && charIndex === current.length) {
            speed = 2000;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIdx = (phraseIdx + 1) % phrases.length;
            speed = 500;
        }

        setTimeout(type, speed);
    }

    type();

    if (typeof gsap !== "undefined") {
        gsap.to("#cursor", {
            opacity: 0,
            repeat: -1,
            yoyo: true,
            duration: 0.5
        });
    }
}


/* =======================================================
   3️⃣ EFECTO 3D GALERÍA (GSAP TILT)
======================================================= */
function initGalleryTilt() {
    if (typeof gsap === "undefined") return;

    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('mousemove', (e) => {
            const rect = item.getBoundingClientRect();
            const x = (e.clientX - rect.left - rect.width / 2) / (rect.width / 2) * 15;
            const y = (e.clientY - rect.top - rect.height / 2) / (rect.height / 2) * -15;

            gsap.to(item, {
                rotateY: x,
                rotateX: y,
                scale: 1.05,
                duration: 0.4,
                ease: "power2.out"
            });
        });

        item.addEventListener('mouseleave', () => {
            gsap.to(item, {
                rotateY: 0,
                rotateX: 0,
                scale: 1,
                duration: 0.5
            });
        });
    });
}


/* =======================================================
   4️⃣ MENÚ LATERAL
======================================================= */
function initMenu() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const menuClose = document.getElementById('menuClose');

    if (!menuToggle || !sidebarMenu || !menuClose) return;

    menuToggle.addEventListener('click', () => {
        sidebarMenu.classList.add('active');
    });

    menuClose.addEventListener('click', () => {
        sidebarMenu.classList.remove('active');
    });

    sidebarMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            sidebarMenu.classList.remove('active');
        });
    });

    document.addEventListener('click', (e) => {
        if (!sidebarMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebarMenu.classList.remove('active');
        }
    });
}

