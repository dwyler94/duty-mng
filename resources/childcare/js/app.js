/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import moment from "moment";
import App from "./App.vue";
import VueI18n from 'vue-i18n';
import Swal from "sweetalert2";
/**
 * Routes imports and assigning
 */
import VueRouter from "vue-router";
import Vue from "vue";
import store from "./store";
import router from './router';
import 'vue-loading-overlay/dist/vue-loading.css';

window.Vue = require('vue').default;

moment.locale("ja", {
    weekdays: [
        "日曜日",
        "月曜日",
        "火曜日",
        "水曜日",
        "木曜日",
        "金曜日",
        "土曜日"
    ],
    weekdaysShort: ["日", "月", "火", "水", "木", "金", "土"]
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    onOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Toast = Toast;

Vue.use(VueI18n);
Vue.use(VueRouter);

const messages = {
    ja: {
        ...require('./locales/ja.json')
    }
}
const i18n = new VueI18n({
    locale: 'ja',
    messages
});

// Filter Section

Vue.filter("formatDateTime", function(created) {
    return moment(created).format("YYYY年MM月DD日 h:mm");
});
Vue.filter("formatDateTimeW", function(created) {
    return moment(created).format("YYYY年MM月DD日(ddd) h:mm");
});
Vue.filter("formatDate", function(created) {
    return moment(created).format("YYYY年MM月DD日");
});
Vue.filter("formatDateW", function(created) {
    return moment(created).format("YYYY年MM月DD日(ddd)");
});
Vue.filter("formatDay", function(created) {
    return moment(created).format("D日");
});
Vue.filter("formatWeek", function(created) {
    return moment(created).format("ddd");
});

Vue.filter("yesno", value =>
    value
        ? '<i class="fas fa-check green"></i>'
        : '<i class="fas fa-times red"></i>'
);

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store: store,
    render: h => h(App),
    i18n
});
