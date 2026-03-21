// CSS dependencies — bundled by Vite
import 'bootstrap/dist/css/bootstrap.min.css';
import 'glightbox/dist/css/glightbox.min.css';
import 'select2/dist/css/select2.min.css';
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
