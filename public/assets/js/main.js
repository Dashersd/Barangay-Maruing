/**
 * Barangay Maruing Web-Based Information System
 * Client-Side JavaScript Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // Highlight Active Nav Link based on Current Page
    highlightActiveNavLink();

    // Mobile Navbar Hamburger Menu
    initMobileNavbar();
});

/**
 * Initialize responsive mobile hamburger menu across all public pages
 */
function initMobileNavbar() {
    const navbarContainers = document.querySelectorAll('.navbar-container');
    navbarContainers.forEach(container => {
        const menu = container.querySelector('.navbar-menu');
        if (!menu) return;

        let toggleBtn = container.querySelector('.navbar-toggle-btn');
        if (!toggleBtn) {
            toggleBtn = document.createElement('button');
            toggleBtn.className = 'navbar-toggle-btn';
            toggleBtn.setAttribute('type', 'button');
            toggleBtn.setAttribute('aria-label', 'Toggle navigation');
            toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
            container.appendChild(toggleBtn);
        }

        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('open');
            const isOpen = menu.classList.contains('open');
            toggleBtn.innerHTML = isOpen ? '<i class="bi bi-x-lg"></i>' : '<i class="bi bi-list"></i>';
        });

        // Close on clicking outside
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !toggleBtn.contains(e.target) && menu.classList.contains('open')) {
                menu.classList.remove('open');
                toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
            }
        });
    });
}

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


/**
 * Gallery Carousel Auto-Slide Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const galleryCarousel = document.getElementById('galleryCarousel');

    if (galleryCarousel) {
        setInterval(() => {
            const firstItem = galleryCarousel.querySelector('.gallery-item');
            if (!firstItem) return;
            
            const itemWidth = firstItem.offsetWidth + 20; // width + gap
            
            // Smoothly scroll to the next item
            galleryCarousel.scrollBy({ left: itemWidth, behavior: 'smooth' });
            
            // Wait for the smooth scroll animation to finish (approx 500ms)
            setTimeout(() => {
                // Move the first item to the very end of the line
                galleryCarousel.appendChild(firstItem);
                // Instantly jump the scrollbar back to compensate for the moved item
                galleryCarousel.scrollBy({ left: -itemWidth, behavior: 'auto' });
            }, 600);
            
        }, 3000); 
    }
});
