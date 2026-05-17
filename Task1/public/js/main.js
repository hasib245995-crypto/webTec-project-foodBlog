// public/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    // ── Mobile hamburger ──────────────────────────────────────────────────────
    const hamburger = document.getElementById('hamburger');
    const navLinks  = document.querySelector('.nav-links');
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });
    }

    // ── Auto-dismiss alerts after 5 seconds ────────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
