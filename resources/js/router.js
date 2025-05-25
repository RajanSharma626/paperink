import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";  // Pinia store to manage auth state

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
  { path: "/resume-editor/:template", name: "Editor", component: ResumeEditor },
  { path: "/cover-editor/:template", name: "CoverEditor", component: CoverEditor },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard to protect the Resume and Cover Letter Editors
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();  // Use Pinia store to manage auth state

  // If the route is protected and the user is not authenticated
  if ((to.name === 'Editor' || to.name === 'CoverEditor') && !authStore.isAuthenticated) {
    // Redirect to Home page with login modal visible
    next({ name: 'Home', query: { showLogin: 'true', redirectTo: to.fullPath } });
  } else {
    next();  // Proceed to the route
  }
});

export default router;
