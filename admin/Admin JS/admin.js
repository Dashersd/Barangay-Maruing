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
                        mapContainer.appendChild(previewMarker);
                        
                        // Default position center
                        updatePreviewPosition(50, 50);
                        
                        // Make it draggable
                        makeDraggable(previewMarker);
                    }
                    previewMarker.src = event.target.result;
                    updatePreviewSize();
                };
                reader.readAsDataURL(file);
            }
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
        
        element.addEventListener('mousedown', function(e) {
            isDragging = true;
            element.style.cursor = 'grabbing';
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const rect = mapContainer.getBoundingClientRect();
            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;
            
            // Constrain to map bounds
            x = Math.max(0, Math.min(x, rect.width));
            y = Math.max(0, Math.min(y, rect.height));
            
            // Convert to percentages
            const leftPercent = (x / rect.width) * 100;
            const topPercent = (y / rect.height) * 100;
            
            updatePreviewPosition(topPercent, leftPercent);
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                element.style.cursor = 'grab';
            }
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
});
