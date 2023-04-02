import './bootstrap';
import '../css/app.css';
import '@mdi/font/css/materialdesignicons.css'

import { createApp, h } from 'vue'
import app from './Layouts/App.vue'
import router from './router.js'
import store from './store'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import swal from 'sweetalert2'


const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi', // This is already the default value - only for display purposes
      },
})

window.Swal = swal;

createApp(app)
    .use(router)
    .use(store)
    .use(vuetify)
    .mount("#app")


/* 
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'; */

/* createInertiaApp({
    title: (title) => `${title} - ${appName}`, */
    //resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
   /*  setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}); */
