document.addEventListener('DOMContentLoaded', function() {
    const spotMapImg = document.getElementById('spot-map-img');
    const maruingPin = document.getElementById('maruing-pin-btn');
    const btnBackOverview = document.getElementById('btn-back-overview');
    const backOverviewWrapper = document.getElementById('back-overview-wrapper');
    const btnViewFullImage = document.getElementById('btn-view-full-image');
    
    const overviewImgSrc = 'public/image/Maruing Map/Maruing.jpg';
    const detailedImgSrc = 'public/image/Maruing Map/Aerial View.png';

    const purokButtons = document.querySelectorAll('.purok-chip-btn.has-image');

    function clearActivePurok() {
        document.querySelectorAll('.purok-chip-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
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
            if (btnElement) btnElement.classList.add('active');
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
});
