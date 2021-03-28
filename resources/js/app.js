require('./bootstrap');

window.Vue = require('vue').default;

import router from "./router";

Vue.component('navbar-ad', require('./components/comun/navbar-ad.vue').default);


const app = new Vue({
    el: '#app',
    router
});
