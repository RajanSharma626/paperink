import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

import Resume from "./views/Resume.vue";
import CoverLetter from "./views/CoverLetter.vue";
import Home from "./views/Home.vue";
import ResumeEditor from "./views/ResumeEditor.vue";
import CoverEditor from "./views/CoverEditor.vue";
import Dashboard from "./views/Dashboard.vue"; // Make sure this is the correct file
import ResumeView from "./views/ResumeView.vue"; // Make sure this is the correct file

const routes = [
    { path: "/", name: "Home", component: Home },
    { path: "/home", name: "HomeAlias", component: Home },
    { path: "/resume", name: "Resume", component: Resume },
    { path: "/cover-letter", name: "CoverLetter", component: CoverLetter },
    {
        path: "/resume-editor/:template",
        name: "Editor",
        component: ResumeEditor,
    },
    {
        path: "/cover-editor/:template",
        name: "CoverEditor",
        component: CoverEditor,
    },
    { path: "/dashboard", name: "Dashboard", component: Dashboard },
    {
        path: "/resume/:id/view",
        name: "ResumeView",
        component: ResumeView,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guard to protect the Resume, Cover Letter Editors, and Dashboard
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // Protect Editor, CoverEditor, and Dashboard routes
    if (
        (to.name === "Editor" ||
            to.name === "CoverEditor" ||
            to.name === "Dashboard") &&
        !authStore.isAuthenticated
    ) {
        next({
            name: "Home",
            query: { showLogin: "true", redirectTo: to.fullPath },
        });
    } else {
        next();
    }
});

export default router;
