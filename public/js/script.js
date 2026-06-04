/* ============================================================
   Narratio Blog — script.js
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ── Navbar scroll effect ──────────────────────────────── */
    const nav = document.getElementById('mainNav');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 40);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ── Back to Top ───────────────────────────────────────── */
    const btt = document.getElementById('backToTop');
    if (btt) {
        window.addEventListener('scroll', () => {
            btt.classList.toggle('visible', window.scrollY > 400);
        }, { passive: true });
        btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    /* ── Reading Progress Bar ──────────────────────────────── */
    const article = document.getElementById('articleBody');
    if (article) {
        const bar = document.createElement('div');
        bar.className = 'reading-progress';
        document.body.prepend(bar);

        window.addEventListener('scroll', () => {
            const rect  = article.getBoundingClientRect();
            const total = article.offsetHeight - window.innerHeight;
            const scrolled = Math.max(0, -rect.top);
            bar.style.width = Math.min(100, (scrolled / total) * 100) + '%';
        }, { passive: true });
    }

    /* ── Flash message auto-dismiss ───────────────────────── */
    const flash = document.querySelector('.alert-dismissible');
    if (flash) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(flash);
            bsAlert.close();
        }, 5000);
    }

    /* ── Newsletter subscribe ──────────────────────────────── */
    const subBtn = document.getElementById('subscribeBtn');
    if (subBtn) {
        subBtn.addEventListener('click', () => {
            const input = subBtn.previousElementSibling;
            if (!input || !input.value.includes('@')) {
                showToast('Masukkan email yang valid.', 'danger');
                return;
            }
            subBtn.disabled = true;
            subBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
            showToast('Terima kasih! Anda berhasil subscribe.', 'success');
            input.value = '';
            setTimeout(() => {
                subBtn.disabled = false;
                subBtn.innerHTML = '<i class="bi bi-send-fill"></i>';
            }, 3000);
        });
    }

    /* ── Smooth scroll for anchor links ───────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ── Lazy image reveal (IntersectionObserver) ──────────── */
    const lazyItems = document.querySelectorAll('.post-card, .featured-card, .category-card');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.animationPlayState = 'running';
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        lazyItems.forEach(el => {
            el.style.animationPlayState = 'paused';
            io.observe(el);
        });
    }

    /* ── External links ────────────────────────────────────── */
    document.querySelectorAll('a[href^="http"]').forEach(a => {
        if (!a.hostname || a.hostname !== location.hostname) {
            a.setAttribute('target', '_blank');
            a.setAttribute('rel', 'noopener noreferrer');
        }
    });

    /* ── Confirm delete links ──────────────────────────────── */
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            if (!confirm(el.dataset.confirm)) e.preventDefault();
        });
    });

});

/* ── Toast notification helper ─────────────────────────── */
function showToast(message, type = 'info') {
    const container = getOrCreateToastContainer();
    const id   = 'toast-' + Date.now();
    const icon = type === 'success' ? 'check-circle-fill' : type === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill';

    const html = `
    <div id="${id}" class="toast align-items-center text-bg-${type} border-0 mb-2" role="alert">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-${icon}"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    const toastEl = document.getElementById(id);
    const toast   = new bootstrap.Toast(toastEl, { delay: 4000 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

function getOrCreateToastContainer() {
    let c = document.getElementById('toast-container');
    if (!c) {
        c = document.createElement('div');
        c.id = 'toast-container';
        c.style.cssText = 'position:fixed;bottom:5rem;right:1.5rem;z-index:9999;';
        document.body.appendChild(c);
    }
    return c;
}
