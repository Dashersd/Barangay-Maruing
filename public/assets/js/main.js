/**
 * Barangay Maruing Web-Based Information System
 * Client-Side JavaScript Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // Highlight Active Nav Link based on Current Page
    highlightActiveNavLink();
});

/**
 * Detect current page and mark corresponding navigation link as active
 */
function highlightActiveNavLink() {
    const currentPath = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link, .navbar-nav .btn-register');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (!href) return;
        const targetPage = href.split('/').pop();

        const isCurrentHome = (currentPath === '' || currentPath === 'index.php' || currentPath === 'index.html');
        const isTargetHome = (targetPage === 'index.php' || targetPage === 'index.html' || targetPage === './' || targetPage === '');

        if (isCurrentHome && isTargetHome) {
            link.classList.add('active');
        } else if (targetPage === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

/**
 * Toggle password input visibility (text / password)
 */
function togglePasswordVisibility(inputId, buttonEl) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const icon = buttonEl.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
}

