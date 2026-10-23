document.addEventListener('DOMContentLoaded', function () {
    const spotMapImg = document.getElementById('spot-map-img');
    const maruingPin = document.getElementById('maruing-pin-btn');
    const pinTooltipText = document.getElementById('pin-tooltip-text') || (maruingPin ? maruingPin.querySelector('.pin-tooltip') : null);
    const btnBackOverview = document.getElementById('btn-back-overview');
    const backOverviewWrapper = document.getElementById('back-overview-wrapper');
    const btnViewFullImage = document.getElementById('btn-view-full-image');
    const markersContainer = document.getElementById('map-markers-container');
    const purokPinsContainer = document.getElementById('purok-pins-container');
    const legendPinsContainer = document.getElementById('legend-pins-container');
    const purokMapPins = document.querySelectorAll('.purok-map-pin');
    const btnShowAerial = document.getElementById('btn-show-aerial');

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
    let isPurokView = false;
    let isAerialView = false;

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
        isPurokView = false;
        isAerialView = true;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function () {
            spotMapImg.src = detailedImgSrc;
            spotMapImg.alt = 'Detailed Aerial Map of Barangay Maruing';
            spotMapImg.style.opacity = '1';
            if (maruingPin) maruingPin.style.display = 'none';
            if (purokPinsContainer) purokPinsContainer.style.display = 'block';
            if (legendPinsContainer) legendPinsContainer.style.display = 'block';
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
            if (btnBackOverview) {
                btnBackOverview.innerHTML = '<i class="bi bi-arrow-left"></i> Regional Overview';
                btnBackOverview.setAttribute('title', 'Return to Regional Sequence');
            }
            if (btnViewFullImage) btnViewFullImage.href = detailedImgSrc;
            clearActivePurok();
            clearMarkers();
        }, 180);
    }

    function showOverviewMap() {
        if (!spotMapImg) return;
        currentSequenceIndex = 0;
        isPurokView = false;
        isAerialView = false;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function () {
            const step0 = mapSequence[0];
            spotMapImg.src = step0.src;
            spotMapImg.alt = step0.alt;
            spotMapImg.style.opacity = '1';
            if (purokPinsContainer) purokPinsContainer.style.display = 'none';
            if (legendPinsContainer) legendPinsContainer.style.display = 'none';
            applyPinStep(0);
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'none';
            if (btnViewFullImage) btnViewFullImage.href = step0.src;
            clearActivePurok();
            clearMarkers();
        }, 180);
    }

    function showPurokMap(imgSrc, purokNum, btnElement) {
        if (!spotMapImg || !imgSrc) return;
        isPurokView = true;
        isAerialView = false;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function () {
            spotMapImg.src = imgSrc;
            spotMapImg.alt = 'Map of Purok ' + purokNum + ', Barangay Maruing';
            spotMapImg.style.opacity = '1';
            if (maruingPin) maruingPin.style.display = 'none';
            if (purokPinsContainer) purokPinsContainer.style.display = 'none';
            if (legendPinsContainer) legendPinsContainer.style.display = 'none';
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
            if (btnBackOverview) {
                btnBackOverview.innerHTML = '<i class="bi bi-arrow-left"></i> Back to Aerial Map (9 Puroks)';
                btnBackOverview.setAttribute('title', 'Return to Aerial Map with 9 Purok Pins');
            }
            if (btnViewFullImage) btnViewFullImage.href = imgSrc;
            clearActivePurok();
            clearMarkers();

            if (btnElement) {
                btnElement.classList.add('active');
            } else {
                const targetBtn = document.querySelector('.purok-chip-btn[data-purok="' + purokNum + '"]');
                if (targetBtn) targetBtn.classList.add('active');
            }

            fetchAndPlotMarkers(purokNum);
        }, 180);
    }

    // Sidebar Purok Buttons Click Event
    purokButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const imgSrc = this.getAttribute('data-img');
            const purokNum = this.getAttribute('data-purok');
            if (imgSrc) {
                showPurokMap(imgSrc, purokNum, this);
            }
        });
    });

    // 9 Purok Map Pins Click Event on Aerial Map
    purokMapPins.forEach(function (pin) {
        function activatePin(e) {
            if (e) e.stopPropagation();
            const purokNum = pin.getAttribute('data-purok');
            const imgSrc = pin.getAttribute('data-img');
            const matchingBtn = document.querySelector('.purok-chip-btn[data-purok="' + purokNum + '"]');
            if (imgSrc) {
                showPurokMap(imgSrc, purokNum, matchingBtn);
            }
        }

        pin.addEventListener('click', activatePin);
        pin.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                activatePin(e);
            }
        });
    });

    // Sidebar View Aerial Map Button
    if (btnShowAerial) {
        btnShowAerial.addEventListener('click', function () {
            showDetailedMap();
        });
    }

    // Advance sequence ONLY when Regional Pin is clicked
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
                if (purokPinsContainer) purokPinsContainer.style.display = 'none';
                if (legendPinsContainer) legendPinsContainer.style.display = 'none';
                applyPinStep(currentSequenceIndex);
                if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
                if (btnBackOverview) {
                    btnBackOverview.innerHTML = '<i class="bi bi-arrow-left"></i> Regional Overview';
                }
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

    // Back to Overview Button
    if (btnBackOverview) {
        btnBackOverview.addEventListener('click', function () {
            if (isPurokView) {
                // Return back to Aerial Map with 9 pins
                showDetailedMap();
            } else {
                // Return back to Philippines sequence map
                showOverviewMap();
            }
        });
    }

    // Household Modal Close Logic
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

    window.locateLandmarkOnMap = function(legendId) {
        if (!legendId) return;

        // Ensure we are viewing the Aerial Map
        if (!isAerialView) {
            showDetailedMap();
        }

        // Wait brief delay for map to switch and render
        setTimeout(function() {
            const pin = document.getElementById('legend-pin-' + legendId);
            if (pin) {
                pin.classList.remove('pin-pulse-active');
                void pin.offsetWidth; // Force CSS reflow to replay animation
                pin.classList.add('pin-pulse-active');

                // Smoothly scroll container into view if needed
                const visual = document.querySelector('.spot-map-visual');
                if (visual) {
                    visual.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                setTimeout(function() {
                    pin.classList.remove('pin-pulse-active');
                }, 4000);
            }
        }, 260);
    };

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
