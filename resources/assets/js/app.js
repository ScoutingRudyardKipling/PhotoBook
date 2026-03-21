// CSS dependencies — bundled by Vite
import 'bootstrap/dist/css/bootstrap.min.css';
import 'magnific-popup/dist/magnific-popup.css';
import 'select2/dist/css/select2.min.css';
import '../sass/app.scss';

import './bootstrap.js';
import 'magnific-popup';
import { Tooltip } from 'bootstrap';

// Bootstrap 5 tooltip initialisation
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new Tooltip(el);
    });
});

// Magnific Popup image gallery
jQuery('.js-gallery:not(.js-gallery-enabled)').each(function () {
    let el = jQuery(this);
    el.addClass('js-gallery-enabled');
    el.magnificPopup({
        delegate: 'a.img-lightbox',
        type: 'image',
        gallery: { enabled: true },
    });
});
