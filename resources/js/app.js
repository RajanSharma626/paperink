
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from 'pinia';

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";
import "../css/app.css";
import "bootstrap";
import CKEditor from '@ckeditor/ckeditor5-vue'



const app = createApp(App);

const pinia = createPinia(); // ✅ create Pinia instance

app.use(CKEditor)
app.use(pinia);              // ✅ tell Vue to use Pinia
app.use(router);     
app.mount('#app');
