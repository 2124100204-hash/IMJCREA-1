/* ── THREE.JS WARM PARTICLES ── */
const canvas   = document.getElementById('bg-canvas');
const renderer = new THREE.WebGLRenderer({ canvas, antialias:true, alpha:true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio,2));
renderer.setSize(window.innerWidth, window.innerHeight);
const scene  = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(60, window.innerWidth/window.innerHeight, 0.1, 1000);
camera.position.z = 5;

const N   = 1200, geo = new THREE.BufferGeometry();
const pos = new Float32Array(N*3), col = new Float32Array(N*3);
const pal = [[0.91,0.51,0.05],[0.05,0.48,0.43],[0.79,0.31,0.43],[0.96,0.66,0.26]];
for(let i=0;i<N;i++){
    pos[i*3]=(Math.random()-0.5)*22; pos[i*3+1]=(Math.random()-0.5)*22; pos[i*3+2]=(Math.random()-0.5)*8;
    const p=pal[Math.floor(Math.random()*pal.length)];
    col[i*3]=p[0]; col[i*3+1]=p[1]; col[i*3+2]=p[2];
}
geo.setAttribute('position', new THREE.BufferAttribute(pos,3));
geo.setAttribute('color',    new THREE.BufferAttribute(col,3));
const pts = new THREE.Points(geo, new THREE.PointsMaterial({ size:0.05, vertexColors:true, transparent:true, opacity:0.5 }));
scene.add(pts);

let t=0;
(function frame(){ requestAnimationFrame(frame); t+=0.0005; pts.rotation.y=t; pts.rotation.x=t*0.3; renderer.render(scene,camera); })();
window.addEventListener('resize',()=>{ camera.aspect=window.innerWidth/window.innerHeight; camera.updateProjectionMatrix(); renderer.setSize(window.innerWidth,window.innerHeight); });

/* ── MOBILE NAVBAR ── */
const navToggle = document.getElementById('navToggle');
const navLinks  = document.getElementById('navLinks');
navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));

/* ── FILTER ── */
const filterBtns = document.querySelectorAll('.filter-btn');
const bookCards  = document.querySelectorAll('.book-card');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        bookCards.forEach(card => {
            const show = filter === 'all' || card.dataset.type === filter;
            card.style.display = show ? '' : 'none';
            if (show) {
                // re-trigger animation
                card.classList.remove('visible');
                setTimeout(() => card.classList.add('visible'), 50);
            }
        });
    });
});

/* ── SCROLL REVEAL ── */
const revObs = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            setTimeout(() => e.target.classList.add('visible'), i * 80);
            revObs.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });
bookCards.forEach(el => revObs.observe(el));
