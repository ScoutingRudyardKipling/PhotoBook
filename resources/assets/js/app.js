/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('bootstrap-material-design');
require('magnific-popup');
jQuery('.js-gallery:not(.js-gallery-enabled)').each(function() {
    let el = jQuery(this);

    // Add .js-gallery-enabled class to tag it as activated
    el.addClass('js-gallery-enabled');

    // Init
    el.magnificPopup({
        delegate: 'a.img-lightbox',
        type: 'image',
        gallery: {
            enabled: true
        }
    });
});

//
// window.Vue = require('vue');
//
// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */
//
// // const files = require.context('./', true, /\.vue$/i);
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
//
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//
// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */
//
// const app = new Vue(
//     {
//         el: '#app',
//     }
// );
