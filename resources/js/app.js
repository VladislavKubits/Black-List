/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
// import router from './router';
// import App from './components/App';

// import route from './route';
// import Lang from './lang';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('people-component', require('./components/PeopleComponent.vue').default);

import App from './components/App.vue';
import HeaderComponent from './components/HeaderComponent.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);

import routes from './router';
import store from './store/index';

const router = new VueRouter({
    routes,
    mode: 'history'
});

new Vue({
    el: '#app',
    router,
    store,
    components: {
        'app-component': App,
        'header-component': HeaderComponent
    },
    computed: {
        user() {
            return this.$store.state.user;
        }
    },
    mounted() {
        store.commit('url');
        store.dispatch('users');
    }
});
