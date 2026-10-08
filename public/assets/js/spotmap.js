document.addEventListener('DOMContentLoaded', function() {
    const spotMapImg = document.getElementById('spot-map-img');
    const maruingPin = document.getElementById('maruing-pin-btn');
    const btnBackOverview = document.getElementById('btn-back-overview');
    const backOverviewWrapper = document.getElementById('back-overview-wrapper');
    const btnViewFullImage = document.getElementById('btn-view-full-image');
    const markersContainer = document.getElementById('map-markers-container');
    
    const overviewImgSrc = 'public/image/Maruing Map/Maruing.jpg';
    const detailedImgSrc = 'public/image/Maruing Map/Aerial View.png';

    const purokButtons = document.querySelectorAll('.purok-chip-btn.has-image');

    function clearActivePurok() {
        document.querySelectorAll('.purok-chip-btn').forEach(function(btn) {
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
                            img.style.pointerEvents = 'auto'; // allow hover for title
                            img.style.cursor = 'pointer';
                            
                            img.addEventListener('click', function(e) {
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
        setTimeout(function() {
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
        spotMapImg.style.opacity = '0.2';
        setTimeout(function() {
            spotMapImg.src = overviewImgSrc;
            spotMapImg.alt = 'Spot Map of Barangay Maruing';
            spotMapImg.style.opacity = '1';
            if (maruingPin) maruingPin.style.display = 'flex';
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'none';
            if (btnViewFullImage) btnViewFullImage.href = overviewImgSrc;
            clearActivePurok();
            clearMarkers();
        }, 180);
    }

    function showPurokMap(imgSrc, purokNum, btnElement) {
        if (!spotMapImg || !imgSrc) return;
        spotMapImg.style.opacity = '0.2';
        setTimeout(function() {
            spotMapImg.src = imgSrc;
            spotMapImg.alt = 'Map of Purok ' + purokNum + ', Barangay Maruing';
            spotMapImg.style.opacity = '1';
            if (maruingPin) maruingPin.style.display = 'none';
            if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
            if (btnViewFullImage) btnViewFullImage.href = imgSrc;
            clearActivePurok();
            clearMarkers();
            if (btnElement) btnElement.classList.add('active');
            
            // Fetch and plot markers for this purok
            fetchAndPlotMarkers(purokNum);
        }, 180);
    }

    purokButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const imgSrc = this.getAttribute('data-img');
            const purokNum = this.getAttribute('data-purok');
            if (imgSrc) {
                showPurokMap(imgSrc, purokNum, this);
            }
        });
    });

    const mapSequence = [
        'public/image/Maruing Map/Philippines.png',
        'public/image/Maruing Map/Zamboanga del Sur.jpg',
        'public/image/Maruing Map/Lapuyan.gif',
        'public/image/Maruing Map/Maruing.jpg'
    ];
    let currentSequenceIndex = 0;

    if (spotMapImg) {
        spotMapImg.addEventListener('click', function() {
            if (currentSequenceIndex < mapSequence.length - 1) {
                currentSequenceIndex++;
                spotMapImg.style.opacity = '0.2';
                setTimeout(function() {
                    spotMapImg.src = mapSequence[currentSequenceIndex];
                    spotMapImg.style.opacity = '1';
                    
                    if (currentSequenceIndex === mapSequence.length - 1) {
                        if (maruingPin) maruingPin.style.display = 'flex';
                        spotMapImg.style.cursor = 'default';
                    }
                    clearMarkers();
                }, 180);
            }
        });
    }
    if (maruingPin) {
        maruingPin.addEventListener('click', showDetailedMap);
        maruingPin.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                showDetailedMap();
            }
        });
    }

    if (btnBackOverview) {
        btnBackOverview.addEventListener('click', showOverviewMap);
    }

    // Modal Close Logic
    const householdModal = document.getElementById('household-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            if(householdModal) householdModal.style.display = 'none';
        });
    }

    if (householdModal) {
        householdModal.addEventListener('click', function(e) {
            if (e.target === householdModal) {
                householdModal.style.display = 'none';
            }
        });
    }
});
