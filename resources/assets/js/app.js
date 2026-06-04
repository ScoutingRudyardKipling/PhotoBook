// CSS dependencies — bundled by Vite
import 'bootstrap/dist/css/bootstrap.min.css';
import 'glightbox/dist/css/glightbox.min.css';
import '../sass/app.scss';

import './bootstrap.js';
import GLightbox from 'glightbox';
import { Tooltip } from 'bootstrap';

// Bootstrap 5 tooltip initialisation
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new Tooltip(el);
    });
});

// Image gallery lightbox
GLightbox({
    selector: '.glightbox',
    openEffect: 'zoom',
    closeEffect: 'zoom',
});

document.addEventListener('DOMContentLoaded', function () {
    const startBtn = document.getElementById('start-slideshow');
    if (!startBtn) return;

    let slideshowInterval;
    let currentSpeed = 10000; // 10 seconds default

    // Collect images from the gallery
    const getGalleryItems = () => {
        return Array.from(document.querySelectorAll('.glightbox')).map(el => ({
            href: el.getAttribute('href'),
            type: 'image',
            title: el.getAttribute('data-title') || ''
        }));
    };

    // Fisher-Yates shuffle
    const shuffle = (array) => {
        let currentIndex = array.length, randomIndex;
        while (currentIndex != 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;
            [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
        }
        return array;
    };

    startBtn.addEventListener('click', () => {
        const items = shuffle(getGalleryItems());
        if (items.length === 0) return;

        const lightbox = GLightbox({
            elements: items,
            autoplayVideos: true,
        });

        lightbox.on('open', () => {
            startAutoplay(lightbox);
            addSpeedControl(lightbox);
        });

        lightbox.on('close', () => {
            clearInterval(slideshowInterval);
        });

        lightbox.open();
    });

    function startAutoplay(lightbox) {
        clearInterval(slideshowInterval);
        slideshowInterval = setInterval(() => {
            const activeIndex = lightbox.getActiveSlideIndex();
            if (activeIndex < lightbox.elements.length - 1) {
                lightbox.nextSlide();
            } else {
                // Restart with a new shuffle for the next iteration
                const newItems = shuffle(getGalleryItems());
                lightbox.setElements(newItems);
                lightbox.goToSlide(0);
            }
        }, currentSpeed);
    }

    function addSpeedControl(lightbox) {
        if (document.getElementById('slideshow-speed-control')) return;

        const container = document.createElement('div');
        container.id = 'slideshow-speed-control';
        // Using inline styles for quick setup, but scoped to the glass design via CSS
        container.innerHTML = `
            <div style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 9999999; padding: 10px 20px; border-radius: 30px; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <label style="margin:0; font-size: 12px; font-weight: bold;">Afspeelsnelheid:</label>
                <input type="range" min="1" max="30" value="${currentSpeed / 1000}" id="speed-slider" style="cursor: pointer;">
                <span id="speed-value" style="min-width: 30px;">${currentSpeed / 1000} seconden</span>
            </div>
        `;
        document.body.appendChild(container);

        const slider = container.querySelector('#speed-slider');
        const valDisplay = container.querySelector('#speed-value');

        slider.addEventListener('input', (e) => {
            currentSpeed = e.target.value * 1000;
            valDisplay.innerText = `${e.target.value} seconden`;
            startAutoplay(lightbox); // Restart timer with new speed
        });

        lightbox.on('close', () => {
            container.remove();
        });
    }
});
