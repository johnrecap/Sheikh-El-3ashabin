import { createApp } from 'vue';
import DefaultComponent from "./components/DefaultComponent.vue";
import router from './router';
import store from './store';
import axios from 'axios';
import i18n from "./i18n";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import VueSimpleAlert from "vue3-simple-alert";
import VueNextSelect from 'vue-next-select';
import 'vue-next-select/dist/index.css';
import ENV from './config/env';
import "../../public/themes/default/fonts/urbanist/urbanist.css";
import "../../public/themes/default/fonts/iconly/lab.css";
import "../../public/themes/default/fonts/public/public.css";
import "../../public/themes/default/fonts/fontawesome/fontawesome.css";
import { createHead } from '@vueuse/head';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueApexCharts from "vue3-apexcharts";
const head = createHead();

/* Start tooltip alert code */
const toastOptions = {
    timeout: 2000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};
/* End tooltip alert code */


/* Start axios code*/
const API_URL = ENV.API_URL;
const API_KEY = ENV.API_KEY;
axios.defaults.baseURL = '/api';

axios.interceptors.request.use(
    config => {
        config.headers['x-api-key'] = API_KEY;
        if (localStorage.getItem('vuex')) {
            const vuex = JSON.parse(localStorage.getItem('vuex'));
            const token = vuex.auth.authToken;
            config.headers['Authorization'] = token ? `Bearer ${token}` : '';

            if (vuex.globalState) {
                config.headers['x-localization'] = vuex.globalState.lists.language_code;
            }
        }
        return config;
    },
    error => Promise.reject(error),
);


const app = createApp(DefaultComponent);
app.component('vue-select', VueNextSelect)
app.use(router)
app.use(store)
app.use(VueSimpleAlert)
app.use(VueApexCharts)
app.use(Toast, toastOptions)
app.use(i18n)
app.use(head)
app.mount('#app');