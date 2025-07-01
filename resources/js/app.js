import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "../css/app.css";

import PdfPreview from "./Components/PdfPreview.vue";



const app = createApp(App);

const pinia = createPinia();
app.use(pinia);
app.use(router);
// Register the component globally
app.component("PdfPreview", PdfPreview);
app.mount("#app");
