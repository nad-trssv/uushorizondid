import './bootstrap';
import { createApp } from 'vue';
import router from './routes/router';
import { NConfigProvider } from 'naive-ui';
import store from './stores';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { createI18n } from 'vue-i18n';
import { swal } from './utils/swal';



const app = createApp({});
const messages = {
    en: (await import('../../lang/php_en.json')).default,
    ru: (await import('../../lang/php_ru.json')).default,
    lv: (await import('../../lang/php_lv.json')).default,
    et: (await import('../../lang/php_et.json')).default,
};

const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem('locale') || 'en',
    fallbackLocale: 'en',
    messages
});

import Index from './components/Index.vue';
import RichEditor from './components/elements/RichEditor.vue';

app.component('rich-editor', RichEditor);
app.component('index-component', Index);
app.component('n-config-provider', NConfigProvider); 
app.use(swal);
app.use(i18n);
app.use(store);
app.use(router);

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

app.mount('#app');
