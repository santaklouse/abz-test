import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue';
// import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// BootstrapVue 3
import * as BootstrapVue3 from 'bootstrap-vue-next';
// Bootstrap стили
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css';

const app = createApp(App);
app.use(BootstrapVue3);
// app.use(IconsPlugin);

app.mount('#app');
