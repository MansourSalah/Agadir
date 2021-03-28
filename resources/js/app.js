require('./bootstrap');

window.Vue = require('vue').default;

import router from "./router";

Vue.component('navbar-ad', require('./components/comun/navbar-ad.vue').default);
Vue.component('navbar', require('./components/comun/navbar.vue').default);
Vue.component('foot', require('./components/comun/foot.vue').default);


const app = new Vue({
    el: '#app',
    router
});
