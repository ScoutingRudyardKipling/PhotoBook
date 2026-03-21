import _ from 'lodash';
window._ = _;

/**
 * jQuery is loaded globally for Select2 and Magnific Popup.
 * Bootstrap 5 does not require jQuery but works alongside it.
 */
import $ from 'jquery';
window.$ = window.jQuery = $;

import 'bootstrap';
import 'select2';

/**
 * Axios HTTP library with CSRF token attached automatically.
 */
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
