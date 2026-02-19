const dot  = document.getElementById('cursorDot');
const ring = document.getElementById('cursorRing');
let mouseX=0, mouseY=0, ringX=0, ringY=0;

document.addEventListener('mousemove', e => {
    mouseX = e.clientX; mouseY = e.clientY;
    dot.style.transform = `translate(${mouseX-4}px,${mouseY-4}px)`;
});
(function loop() {
    ringX += (mouseX-ringX)*0.12;
    ringY += (mouseY-ringY)*0.12;
    ring.style.transform = `translate(${ringX-18}px,${ringY-18}px)`;
    requestAnimationFrame(loop);
})();
document.querySelectorAll('a,button,.card,.menu-toggle').forEach(el => {
    el.addEventListener('mouseenter', () => { ring.style.width='56px'; ring.style.height='56px'; ring.style.borderColor='var(--neon-violet)'; });
    el.addEventListener('mouseleave', () => { ring.style.width='36px'; ring.style.height='36px'; ring.style.borderColor='var(--neon-cyan)'; });
});

/* ════════════════════════════════════
   MENU
════════════════════════════════════ */
const toggle   = document.getElementById('menuToggle');
const sidebar  = document.getElementById('sidebarMenu');
const backdrop = document.getElementById('menuBackdrop');
const openMenu  = () => { toggle.classList.add('open'); sidebar.classList.add('active'); backdrop.classList.add('active'); };
const closeMenu = () => { toggle.classList.remove('open'); sidebar.classList.remove('active'); backdrop.classList.remove('active'); };
toggle.addEventListener('click', () => toggle.classList.contains('open') ? closeMenu() : openMenu());
backdrop.addEventListener('click', closeMenu);
sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

/* ════════════════════════════════════
   THREE.JS PARTICLES
════════════════════════════════════ */
const canvas   = document.getElementById('bg-canvas');
const renderer = new THREE.WebGLRenderer({ canvas, antialias:true, alpha:true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio,2));
renderer.setSize(window.innerWidth, window.innerHeight);
const scene  = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(60, window.innerWidth/window.innerHeight, 0.1, 1000);
camera.position.z = 5;

const N = 2000, geo = new THREE.BufferGeometry();
const pos = new Float32Array(N*3), col = new Float32Array(N*3);
for (let i=0;i<N;i++) {
    pos[i*3]   = (Math.random()-0.5)*20;
    pos[i*3+1] = (Math.random()-0.5)*20;
    pos[i*3+2] = (Math.random()-0.5)*10;
    const t = Math.random();
    col[i*3]   = t<0.5?0:0.75;
    col[i*3+1] = t<0.5?1:0.37;
    col[i*3+2] = t<0.5?0.88:1;
}
geo.setAttribute('position', new THREE.BufferAttribute(pos,3));
geo.setAttribute('color',    new THREE.BufferAttribute(col,3));
const pts = new THREE.Points(geo, new THREE.PointsMaterial({ size:0.04, vertexColors:true, transparent:true, opacity:0.6 }));
scene.add(pts);

const gm = new THREE.LineBasicMaterial({ color:0x00ffe1, transparent:true, opacity:0.03 });
for (let i=-10;i<=10;i+=2) {
    scene.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(-10,i,-5),new THREE.Vector3(10,i,-5)]),gm));
    scene.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(i,-10,-5),new THREE.Vector3(i,10,-5)]),gm));
}
let mx=0, my=0, t=0;
document.addEventListener('mousemove', e => { mx=(e.clientX/window.innerWidth-0.5)*0.3; my=(e.clientY/window.innerHeight-0.5)*0.3; });
(function frame() { requestAnimationFrame(frame); t+=0.0008; pts.rotation.y=t+mx; pts.rotation.x=my*0.5; renderer.render(scene,camera); })();
window.addEventListener('resize', () => { camera.aspect=window.innerWidth/window.innerHeight; camera.updateProjectionMatrix(); renderer.setSize(window.innerWidth,window.innerHeight); });

/* ════════════════════════════════════
   TYPEWRITER
════════════════════════════════════ */
const phrases  = ['Libros que cobran vida.','Experiencias sin límites.','Crea. Imagina. Explora.','El futuro de la lectura.','Tu creatividad, amplificada.'];
const typeEl   = document.getElementById('typewriterText');
let pIdx=0, cIdx=0, del=false;

function tw() {
    const cur = phrases[pIdx];
    if (!del) {
        typeEl.textContent = cur.substring(0, ++cIdx);
        if (cIdx === cur.length) { del=true; setTimeout(tw, 2000); return; }
    } else {
        typeEl.textContent = cur.substring(0, --cIdx);
        if (cIdx === 0) { del=false; pIdx=(pIdx+1)%phrases.length; }
    }
    setTimeout(tw, del?50:80);
}
setTimeout(tw, 1800);

/* ════════════════════════════════════
   COUNTERS
════════════════════════════════════ */
function animCounter(el) {
    const target=parseInt(el.dataset.target), pre=el.dataset.prefix||'', suf=el.dataset.suffix||'';
    let cur=0;
    const inc = target/125;
    const id = setInterval(() => {
        cur = Math.min(cur+inc, target);
        el.textContent = pre + Math.floor(cur) + suf;
        if (cur>=target) clearInterval(id);
    }, 16);
}
const cntObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { animCounter(e.target); cntObs.unobserve(e.target); } });
}, { threshold:0.5 });
document.querySelectorAll('[data-target]').forEach(el => cntObs.observe(el));

/* ════════════════════════════════════
   MODAL
════════════════════════════════════ */
const modalData = {
    1:{ tag:'01 · Tienda',       title:'Tienda Biblioteca',          color:'#00ffe1', body:'Explora cientos de títulos organizados por categoría, nivel educativo y tipo de experiencia. Desde ciencias hasta arte, cada libro ha sido seleccionado para maximizar el impacto creativo. Descarga, accede en streaming o comparte con tu comunidad.' },
    2:{ tag:'02 · Realidad AR',  title:'Libros Realidad Aumentada',  color:'#bf5fff', body:'Apunta la cámara de tu dispositivo a las páginas y observa cómo los personajes, diagramas y escenarios cobran vida tridimensional. Compatible con iOS y Android. Una nueva capa de información se superpone al mundo real, transformando cada lectura.' },
    3:{ tag:'03 · Realidad VR',  title:'Libros Realidad Virtual',    color:'#ff6b35', body:'Ponte el visor y sumérgete completamente dentro de la historia. Camina por los escenarios, interactúa con los personajes y vive la narrativa en primera persona. Compatible con Meta Quest, PSVR y visores PC.' },
    4:{ tag:'04 · Descubrimiento',title:'Explorar',                  color:'#00ffe1', body:'Descubre experiencias recomendadas según tus intereses, conecta con creadores, participa en retos semanales y accede a contenido exclusivo. INMERSIA es también una comunidad de personas que creen en el poder de la imaginación.' }
};

const overlay   = document.getElementById('modalOverlay');
const mClose    = document.getElementById('modalClose');
const mAccent   = document.getElementById('modalAccent');
const mTag      = document.getElementById('modalTag');
const mTitle    = document.getElementById('modalTitle');
const mBody     = document.getElementById('modalBody');

function openModal(id) {
    const d = modalData[id];
    mAccent.style.background = `linear-gradient(90deg,${d.color},transparent)`;
    mTag.style.color  = d.color;
    mTag.textContent  = d.tag;
    mTitle.textContent = d.title;
    mBody.textContent  = d.body;
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal() { overlay.classList.remove('active'); document.body.style.overflow=''; }

document.querySelectorAll('.card').forEach(c => c.addEventListener('click', () => openModal(c.dataset.modal)));
mClose.addEventListener('click', closeModal);
overlay.addEventListener('click', e => { if (e.target===overlay) closeModal(); });
document.addEventListener('keydown', e => { if (e.key==='Escape') closeModal(); });

/* ════════════════════════════════════
   CARD TILT
════════════════════════════════════ */
document.querySelectorAll('.card').forEach(c => {
    c.addEventListener('mousemove', e => {
        const r=c.getBoundingClientRect(), x=(e.clientX-r.left)/r.width-0.5, y=(e.clientY-r.top)/r.height-0.5;
        c.style.transform=`rotateY(${x*8}deg) rotateX(${-y*8}deg)`;
    });
    c.addEventListener('mouseleave', () => { c.style.transform='none'; });
});

/* ════════════════════════════════════
   SCROLL REVEAL
════════════════════════════════════ */
const revObs = new IntersectionObserver(entries => {
    entries.forEach((e,i) => {
        if (e.isIntersecting) {
            setTimeout(() => e.target.classList.add('visible'), i*100);
            revObs.unobserve(e.target);
        }
    });
}, { threshold:0.1 });
document.querySelectorAll('.how-step,.team-card').forEach(el => revObs.observe(el));