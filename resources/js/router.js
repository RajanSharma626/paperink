import { createRouter, createWebHistory } from "vue-router";

import Resume from "./views/Resume.vue";
import CoverLetter from "./views/CoverLetter.vue";
import Home from "./views/Home.vue";
import ResumeEditor from "./views/ResumeEditor.vue";
import CoverEditor from "./views/CoverEditor.vue";

const routes = [
    { path: "/", name: "Home", component: Home },
    { path: "/home", name: "HomeAlias", component: Home },
    { path: "/resume", name: "Resume", component: Resume },
    { path: "/cover-letter", name: "CoverLetter", component: CoverLetter },
    { path: "/resume-editor/:template", name: "Editor", component: ResumeEditor,},
    { path: "/cover-editor/:template", name: "CoverEditor", component: CoverEditor,},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
