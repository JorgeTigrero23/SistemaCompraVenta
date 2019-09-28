
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('category-component', require('./components/CategoryComponent.vue'));
Vue.component('product-component', require('./components/ProductComponent.vue'));
Vue.component('client-component', require('./components/ClientComponent.vue'));
Vue.component('provider-component', require('./components/ProviderComponent.vue'));
Vue.component('rol-component', require('./components/RolComponent.vue'));
Vue.component('user-component', require('./components/UserComponent.vue'));
Vue.component('purchase-component', require('./components/PurchaseComponent.vue'));
Vue.component('sale-component', require('./components/SaleComponent.vue'));
Vue.component('report-purchase-component', require('./components/ReportPurchaseComponent.vue'));
Vue.component('report-sale-component', require('./components/ReportSaleComponent.vue'));
Vue.component('notification-component', require('./components/NotificationComponent.vue'));
Vue.component('dashboard-component', require('./components/DashboardComponent.vue'));

const app = new Vue({
    el: '#app',
    data:{
        menu: 0,
        notifications: []
    },
    created(){
        let me = this;
        axios.post('notification/get').then(function (response) {
            me.notifications = response.data;
        }).catch(function(error){
            console.error(error);
        });

        var userId = $('meta[name="userId"]').attr('content');

        //console.log(userId);

        Echo.private('App.User.' + userId).notification((notification) => {
            me.notifications.unshift(notification); 
        })
    }
});
