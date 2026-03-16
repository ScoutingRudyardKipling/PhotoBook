require('./bootstrap');
require('magnific-popup');

// Bootstrap 5 tooltip initialisation
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
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
