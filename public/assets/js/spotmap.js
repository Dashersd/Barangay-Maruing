document.addEventListener('DOMContentLoaded', function () {
    const spotMapImg = document.getElementById('spot-map-img');
    const maruingPin = document.getElementById('maruing-pin-btn');
    const pinTooltipText = document.getElementById('pin-tooltip-text') || (maruingPin ? maruingPin.querySelector('.pin-tooltip') : null);
    const btnBackOverview = document.getElementById('btn-back-overview');
    const backOverviewWrapper = document.getElementById('back-overview-wrapper');
    const btnViewFullImage = document.getElementById('btn-view-full-image');
    const markersContainer = document.getElementById('map-markers-container');

    const detailedImgSrc = 'public/image/Maruing Map/Aerial View.png';
    const purokButtons = document.querySelectorAll('.purok-chip-btn.has-image');

    const mapSequence = [
        {
            src: 'public/image/Maruing Map/Philippines.png',
            alt: 'Map of the Philippines',
            pin: {
                top: '77.5%',
                left: '61.5%',
                tooltip: 'Click to View Mindanao',
                title: 'Mindanao, Philippines'
            }
        },
        {
            src: 'public/image/Maruing Map/Mindanao.jpg',
            alt: 'Map of Mindanao',
            pin: {
                top: '57%',
                left: '30.5%',
                tooltip: 'Click to View Zamboanga del Sur',
                title: 'Zamboanga del Sur'
            }
        },
        {
            src: 'public/image/Maruing Map/Zamboanga del Sur.jpg',
            alt: 'Map of Zamboanga del Sur',
            pin: {
                top: '64%',
                left: '42.5%',
                tooltip: 'Click to View Lapuyan',
                title: 'Municipality of Lapuyan'
            }
        },
        {
            src: 'public/image/Maruing Map/Lapuyan.gif',
            alt: 'Map of Lapuyan',
            pin: {
                top: '50%',
                left: '50%',
                tooltip: 'Click to View Barangay Maruing',
                title: 'Barangay Maruing'
            }
        },
        {
            src: 'public/image/Maruing Map/Maruing.jpg',
            alt: 'Spot Map of Barangay Maruing',
            pin: {
                top: '34.5%',
                left: '27.5%',
                tooltip: 'Click to View Aerial Map of Maruing',
                title: 'Barangay Maruing Center'
            }
        }
    ];

    let currentSequenceIndex = 0;

    function applyPinStep(index) {
        if (!maruingPin) return;
        const step = mapSequence[index];
        if (!step) return;

        maruingPin.style.top = step.pin.top;
        maruingPin.style.left = step.pin.left;
        maruingPin.setAttribute('title', step.pin.title);
        if (pinTooltipText) {
            pinTooltipText.textContent = step.pin.tooltip;
        }
        maruingPin.style.display = 'flex';
    }

    function clearActivePurok() {
        document.querySelectorAll('.purok-chip-btn').forEach(function (btn) {
            btn.classList.remove('active');
        });
    }

    function clearMarkers() {
        if (markersContainer) {
            markersContainer.innerHTML = '';
        }
    }

    function fetchAndPlotMarkers(purokNum) {
        if (!markersContainer) return;

        fetch('get_markers.php?purok=' + purokNum)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(marker => {
                        const imgSrc = marker.marker_image;
                        if (imgSrc) {
                            const img = document.createElement('img');
                            img.src = imgSrc;
                            img.title = marker.husband_name || '';
                            img.style.position = 'absolute';
                            img.style.top = marker.top_position + '%';
                            img.style.left = marker.left_position + '%';
                            img.style.width = marker.marker_width + 'px';
                            img.style.height = marker.marker_height + 'px';
                            img.style.transform = 'translate(-50%, -100%)';
                            img.style.pointerEvents = 'auto';
                            img.style.cursor = 'pointer';

                            img.addEventListener('click', function (e) {
                                e.stopPropagation();
                                document.getElementById('modal-house-no').textContent = marker.house_number || 'N/A';
                                document.getElementById('modal-husband').textContent = marker.husband_name || 'N/A';
                                document.getElementById('modal-spouse').textContent = marker.spouse_name || 'N/A';

                                const modalHouseImg = document.getElementById('modal-house-img');
                                const modalNoImg = document.getElementById('modal-no-img');

                                if (marker.house_image) {
                                    modalHouseImg.src = marker.house_image;
                                    modalHouseImg.style.display = 'block';
                                    modalNoImg.style.display = 'none';
                                } else {
                                    modalHouseImg.style.display = 'none';
                                    modalNoImg.style.display = 'flex';
                                }

                                const childrenContainer = document.getElementById('modal-children-list');
                                if (childrenContainer) {
                                    let children = [];
                                    try {
                                        children = marker.children ? JSON.parse(marker.children) : [];
                                    } catch(err) {
                                        children = [];
                                    }
                                    if (Array.isArray(children) && children.length > 0) {
                                        if (children.length > 1) {
                                            childrenContainer.innerHTML = `
                                                <ul style="margin: 0; padding-left: 18px; list-style-type: disc; width: 100%; display: flex; flex-direction: column; gap: 4px;">
                                                    ${children.map(c => `
                                                        <li style="color: #0284c7; padding-left: 2px;">
                                                            <span style="color: #334155; font-size: 0.85rem; font-weight: 500;">${escapeHtml(c)}</span>
                                                        </li>
                                                    `).join('')}
                                                </ul>
                                            `;
                                        } else {
                                            childrenContainer.innerHTML = `<span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 500; display: inline-block; align-self: flex-start;">${escapeHtml(children[0])}</span>`;
                                        }
                                    } else {
                                        childrenContainer.innerHTML = '<span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">None listed</span>';
                                    }
                                }

                                document.getElementById('household-modal').style.display = 'flex';
                            });

                            markersContainer.appendChild(img);
                        }
                    });
                }
            })
            .catch(error => console.error('Error fetching markers:', error));
    }

    function showDetailedMap() {
        if (!spotMapImg) return;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function () {
            spotMapImg.src = detailedImgSrc;
            spotMapImg.alt = 'Detailed Street Map of Barangay Maruing';
            spotMapImg.style.opacity = '1';
            if (maruingPin) maruingPin.style.display = 'none';
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
            if (btnViewFullImage) btnViewFullImage.href = detailedImgSrc;
            clearActivePurok();
            clearMarkers();
        }, 180);
    }

    function showOverviewMap() {
        if (!spotMapImg) return;
        currentSequenceIndex = 0;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function () {
            const step0 = mapSequence[0];
            spotMapImg.src = step0.src;
            spotMapImg.alt = step0.alt;
            spotMapImg.style.opacity = '1';
            applyPinStep(0);
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'none';
            if (btnViewFullImage) btnViewFullImage.href = step0.src;
            clearActivePurok();
            clearMarkers();
        }, 180);
    }

    function showPurokMap(imgSrc, purokNum, btnElement) {
        if (!spotMapImg || !imgSrc) return;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function () {
            spotMapImg.src = imgSrc;
            spotMapImg.alt = 'Map of Purok ' + purokNum + ', Barangay Maruing';
            spotMapImg.style.opacity = '1';
            if (maruingPin) maruingPin.style.display = 'none';
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
            if (btnViewFullImage) btnViewFullImage.href = imgSrc;
            clearActivePurok();
            clearMarkers();
            if (btnElement) btnElement.classList.add('active');

            fetchAndPlotMarkers(purokNum);
        }, 180);
    }

    purokButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const imgSrc = this.getAttribute('data-img');
            const purokNum = this.getAttribute('data-purok');
            if (imgSrc) {
                showPurokMap(imgSrc, purokNum, this);
            }
        });
    });

    // Advance sequence ONLY when Pin is clicked
    function onPinClicked(e) {
        if (e) e.stopPropagation();

        if (currentSequenceIndex < mapSequence.length - 1) {
            currentSequenceIndex++;
            const nextStep = mapSequence[currentSequenceIndex];
            spotMapImg.style.opacity = '0.2';

            setTimeout(function () {
                spotMapImg.src = nextStep.src;
                spotMapImg.alt = nextStep.alt;
                spotMapImg.style.opacity = '1';
                applyPinStep(currentSequenceIndex);
                if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
                if (btnViewFullImage) btnViewFullImage.href = nextStep.src;
                clearMarkers();
            }, 180);
        } else {
            // Last sequence map (Maruing.jpg) -> opens Aerial View
            showDetailedMap();
        }
    }

    if (maruingPin) {
        maruingPin.addEventListener('click', onPinClicked);
        maruingPin.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                onPinClicked(e);
            }
        });
    }

    // Initialize Pin on Step 0 (Philippines map)
    applyPinStep(0);

    if (btnBackOverview) {
        btnBackOverview.addEventListener('click', showOverviewMap);
    }

    // Modal Close Logic
    const householdModal = document.getElementById('household-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            if (householdModal) householdModal.style.display = 'none';
        });
    }

    if (householdModal) {
        householdModal.addEventListener('click', function (e) {
            if (e.target === householdModal) {
                householdModal.style.display = 'none';
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
