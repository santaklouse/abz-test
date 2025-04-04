import './bootstrap';
import { createApp } from 'vue';

import App from './components/App.vue';


import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css';

const app = createApp(App);

app.mount('#app');
