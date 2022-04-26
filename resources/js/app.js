require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { createStore } from 'vuex'
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import VueTippy from 'vue-tippy';
import { i18nVue } from 'laravel-vue-i18n'
import BodyScrollLock from '@/Plugins/BodyScrollLock'
import storeState from "./store";

import moment from 'moment'
moment.locale('ru');

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const store = createStore({
    modules: {
        storeState
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {

        let ap = createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(store)
            .use(VueToast)
            .use(BodyScrollLock)
            .use(VueTippy, {
                defaultProps: { placement: 'bottom', theme: 'crm', arrow: false },
            })
            .use(i18nVue, {
                lang: 'ru',
                resolve: lang => import(`../lang/${lang}.json`),
            })
            .mixin({ methods: { route } });

            ap.config.globalProperties.$moment=moment;

            return ap.mount(el);
    },
});

InertiaProgress.init({ color: '#1e293b' });
