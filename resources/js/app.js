/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');

import Vue from 'vue'
import moment from 'moment'
import VueProgressBar from 'vue-progressbar'


//Laravel Gate register for global
import Gate from './Gate'
Vue.prototype.$gate = new Gate(window.user)

// ES6 Modules or TypeScript
//sweetalert2
import Swal from 'sweetalert2'
window.swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

window.toast = Toast;


//For instant change
window.Fire = new Vue();


//ProgressBar
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '5px'
})


//vform
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


//vue router
import VueRouter from 'vue-router'

Vue.use(VueRouter)

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    // { path: '/users', component: require('./components/Users.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/cv-profile', component: require('./components/CvProfile.vue').default },
    { path: '/job-post', component: require('./components/JobPost.vue').default },
    { path: '/find-job', component: require('./components/FindJob.vue').default },
    { path: '/applicant-details/:user_id', component: require('./components/ApplicantDetails.vue').default },
    { path: '/job-details/:id', component: require('./components/JobDetails.vue').default },
    { path: '/job-details-applicants/:id', component: require('./components/ApplicantsView.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default },
]

let router = new VueRouter({
    routes,
    mode: 'history'
})


Vue.filter('capsText', function(text) {
    return text[0].toUpperCase() + text.slice(1);
});

Vue.filter('upText', function(text) {
    return text.toUpperCase();
});

Vue.filter('lowText', function(text) {
    return text.toLowerCase();
});

Vue.filter('myDate', function(text) {
    return moment(text).format('MMMM Do YYYY');
});


//Popperjs
// import { createPopper } from '@popperjs/core';
// const popcorn = document.querySelector('#popcorn');
// const tooltip = document.querySelector('#tooltip');
// createPopper(popcorn, tooltip, {
//     placement: 'bottom-end',
// });

//laravel vue Pagination
Vue.component('pagination', require('laravel-vue-pagination'));



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


// //Passposrt component
// Vue.component(
//     'passport-clients',
//     require('./components/passport/Clients.vue').default
// );

// Vue.component(
//     'passport-authorized-clients',
//     require('./components/passport/AuthorizedClients.vue').default
// );

// Vue.component(
//     'passport-personal-access-tokens',
//     require('./components/passport/PersonalAccessTokens.vue').default
// );


//My component
Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);







const app = new Vue({
    el: '#app',
    router,
    data: {
        search: ""
    },
    methods: {
        searchData: _.debounce(() => {
            Fire.$emit("searching");
        }, 500), //milisecond 
        printDoc() {
            window.print();
        }
    },
});