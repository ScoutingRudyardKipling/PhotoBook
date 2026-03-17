window._ = require('lodash');

/**
 * Bootstrap 5 — jQuery is still loaded for Select2 and Magnific Popup.
 * Bootstrap 5 does not require jQuery but works alongside it.
 */
try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}

/**
 * Axios HTTP library with CSRF token attached automatically.
 */
const _axios = require('axios');
window.axios = _axios.default ?? _axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
