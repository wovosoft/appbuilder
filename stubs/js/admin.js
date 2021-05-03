require('./bootstrap');

// require('alpinejs');
import Vue from 'vue'
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import filters from "./filters";

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(filters);
import TheContainer from "./components/TheContainer";
import router from "./routes"

(new Vue({
    router,
    computed: {
        s() {
            return s();
        },
        baseUrl() {
            return window.baseUrl();
        }
    },
    methods: {
        settings(key) {
            return window.s(key);
        },
        msgBox(data) {
            this.$bvToast.toast(data.message || data.msg, {
                title: data ? data.title : "Operation Failed",
                solid: true,
                variant: data.variant || 'warning',
                appendToast: true
            })
        }
    },
    render: h => h(TheContainer)
})).$mount("#app");

