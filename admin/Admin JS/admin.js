document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="marker_image"]');
    const mapContainer = document.querySelector('.purok-map-container');
    const topInput = document.querySelector('input[name="top_position"]');
    const leftInput = document.querySelector('input[name="left_position"]');
    const widthInput = document.querySelector('input[name="marker_width"]');
    const heightInput = document.querySelector('input[name="marker_height"]');
    
    let previewMarker = null;

    if (fileInput && mapContainer) {
        // Ensure map container is relative for absolute positioning
        mapContainer.style.position = 'relative';

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (!previewMarker) {
                        previewMarker = document.createElement('img');
                        previewMarker.style.position = 'absolute';
                        previewMarker.style.cursor = 'grab';
                        previewMarker.style.transform = 'translate(-50%, -100%)';
                        previewMarker.style.zIndex = '1000';
                        previewMarker.style.touchAction = 'none';
                        mapContainer.appendChild(previewMarker);
                        
                        // Default position center
                        updatePreviewPosition(50, 50);
                        
                        // Make it draggable (mouse + touch)
                        makeDraggable(previewMarker);
                    }
                    previewMarker.src = event.target.result;
                    updatePreviewSize();
                };
                reader.readAsDataURL(file);
            }
        });

        // Allow tapping directly on the map to move the preview marker (great for mobile)
        mapContainer.addEventListener('click', function(e) {
            if (!previewMarker || e.target === previewMarker) return;
            const rect = mapContainer.getBoundingClientRect();
            const x = Math.max(0, Math.min(e.clientX - rect.left, rect.width));
            const y = Math.max(0, Math.min(e.clientY - rect.top, rect.height));
            updatePreviewPosition((y / rect.height) * 100, (x / rect.width) * 100);
        });

        // Listen for width/height changes
        if(widthInput) widthInput.addEventListener('input', updatePreviewSize);
        if(heightInput) heightInput.addEventListener('input', updatePreviewSize);
        
        // Listen for manual top/left changes
        if(topInput) topInput.addEventListener('input', () => updatePreviewPosition(topInput.value, leftInput.value));
        if(leftInput) leftInput.addEventListener('input', () => updatePreviewPosition(topInput.value, leftInput.value));
    }

    function updatePreviewSize() {
        if (previewMarker) {
            previewMarker.style.width = (widthInput ? widthInput.value : 40) + 'px';
            previewMarker.style.height = (heightInput ? heightInput.value : 40) + 'px';
        }
    }

    function updatePreviewPosition(top, left) {
        if (previewMarker) {
            previewMarker.style.top = top + '%';
            previewMarker.style.left = left + '%';
            if (topInput) topInput.value = parseFloat(top).toFixed(2);
            if (leftInput) leftInput.value = parseFloat(left).toFixed(2);
        }
    }

    function makeDraggable(element) {
        let isDragging = false;

        function moveAt(clientX, clientY) {
            const rect = mapContainer.getBoundingClientRect();
            let x = clientX - rect.left;
            let y = clientY - rect.top;

            // Constrain to map bounds
            x = Math.max(0, Math.min(x, rect.width));
            y = Math.max(0, Math.min(y, rect.height));

            // Convert to percentages
            const leftPercent = (x / rect.width) * 100;
            const topPercent = (y / rect.height) * 100;

            updatePreviewPosition(topPercent, leftPercent);
        }
        
        element.addEventListener('mousedown', function(e) {
            isDragging = true;
            element.style.cursor = 'grabbing';
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            moveAt(e.clientX, e.clientY);
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                element.style.cursor = 'grab';
            }
        });

        // Touch events for mobile & tablet pin dragging
        element.addEventListener('touchstart', function(e) {
            if (e.touches.length !== 1) return;
            isDragging = true;
            e.preventDefault();
        }, { passive: false });

        document.addEventListener('touchmove', function(e) {
            if (!isDragging || e.touches.length !== 1) return;
            e.preventDefault();
            moveAt(e.touches[0].clientX, e.touches[0].clientY);
        }, { passive: false });

        document.addEventListener('touchend', function() {
            isDragging = false;
        });
    }

    // Dynamic Children / Dependents Builder
    const btnAddChild = document.getElementById('btn-add-child');
    const childrenContainer = document.getElementById('children-container');

    if (btnAddChild && childrenContainer) {
        btnAddChild.addEventListener('click', function() {
            addChildRow();
        });
    }

    if (childrenContainer) {
        childrenContainer.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.btn-remove-child');
            if (removeBtn) {
                const row = removeBtn.closest('.child-input-row');
                if (row) {
                    row.remove();
                }
            }
        });
    }

    function addChildRow(nameValue = '') {
        if (!childrenContainer) return;
        const row = document.createElement('div');
        row.className = 'child-input-row';
        row.style.cssText = 'display: flex; gap: 8px; align-items: center; margin-bottom: 8px;';

        const safeValue = (nameValue || '').replace(/"/g, '&quot;');
        row.innerHTML = `
            <input type="text" name="children[]" value="${safeValue}" placeholder="Child's Full Name (e.g. Juan Dela Cruz Jr.)" class="form-control" style="flex: 1;" required>
            <button type="button" class="btn-remove-child" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; width: 38px; height: 38px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;" title="Remove Child">&times;</button>
        `;
        childrenContainer.appendChild(row);
        const input = row.querySelector('input');
        if (input && !nameValue) input.focus();
    }

    // Universal Mobile Sidebar & Navigation Handler for Admin Panel
    function initAdminMobileSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) return;

        // Auto-expand submenu if it contains an active link or matches current filename
        const currentPage = decodeURIComponent(window.location.pathname.split('/').pop() || '');
        sidebar.querySelectorAll('.has-submenu').forEach(sub => {
            const links = sub.querySelectorAll('.submenu a');
            links.forEach(a => {
                const href = decodeURIComponent((a.getAttribute('href') || '').split('?')[0]);
                if (a.classList.contains('active') || (currentPage && href === currentPage)) {
                    a.classList.add('active');
                    sub.classList.add('open');
                }
            });
        });

        // 1. Ensure backdrop exists
        let backdrop = document.querySelector('.sidebar-overlay-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'sidebar-overlay-backdrop';
            backdrop.id = 'sidebar-overlay-backdrop';
            document.body.appendChild(backdrop);
        }

        // 2. Ensure close button inside sidebar header on mobile
        const sidebarHeader = sidebar.querySelector('.sidebar-header');
        let closeDrawerBtn = sidebar.querySelector('.sidebar-mobile-close-btn');
        if (!closeDrawerBtn && sidebarHeader) {
            closeDrawerBtn = document.createElement('button');
            closeDrawerBtn.type = 'button';
            closeDrawerBtn.className = 'sidebar-mobile-close-btn';
            closeDrawerBtn.setAttribute('aria-label', 'Close Navigation Menu');
            closeDrawerBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            sidebarHeader.appendChild(closeDrawerBtn);
        }

        // 3. Ensure mobile toggle button exists in topbar
        const topbar = document.querySelector('.topbar');
        const topbarTitle = document.querySelector('.topbar-title');
        let toggleBtn = document.getElementById('btn-admin-mobile-sidebar-toggle');

        if (!toggleBtn && (topbarTitle || topbar)) {
            toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.id = 'btn-admin-mobile-sidebar-toggle';
            toggleBtn.className = 'admin-mobile-toggle-btn';
            toggleBtn.setAttribute('aria-label', 'Toggle Navigation Menu');
            toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
            
            if (topbarTitle) {
                topbarTitle.style.display = 'flex';
                topbarTitle.style.alignItems = 'center';
                topbarTitle.prepend(toggleBtn);
            } else if (topbar) {
                topbar.prepend(toggleBtn);
            }
        }

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        if (closeDrawerBtn) {
            closeDrawerBtn.addEventListener('click', closeSidebar);
        }

        backdrop.addEventListener('click', closeSidebar);

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });

        // Auto close sidebar when window is resized to desktop (>991px)
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991 && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
    }

    initAdminMobileSidebar();
});
