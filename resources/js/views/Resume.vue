<template>
    <div class="container py-5">
        <div class="row">
            <div class="col-6">
                <h1>My Resumes</h1>
                <div class="header-border mt-2 mb-3"></div>
                <small>
                    Pick one of our free resume templates, fill it out, and land that dream job! Create and
                    download your professional resume in less than 5 minutes.
                </small>
            </div>

            <div class="col-12 justify-content-center mt-5">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <router-link class="btn theme-border w-100 theme-clr active"
                                    to="/resume">Resume</router-link>
                            </div>
                            <div class="col-md-4">
                                <router-link class="btn theme-border w-100 theme-clr" to="/cover-letter">Cover
                                    Letter</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-5">
            <!-- Loop through the templates and display each template -->
            <div v-for="template in templates" :key="template.id" class="col-lg-3 col-12 mb-3">
                <a href="javascript:void(0)" @click="checkAuthenticationAndProceed($event, template.slug)">
                    <img :src="template.preview_img" class="img-fluid rounded custom-boc-shadow"
                        alt="Template Preview" />
                </a>
            </div>
        </div>
    </div>

    <!-- Include Login Modal here -->
    <Login ref="loginModalComponent" />
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'
import Login from '@/Components/Login.vue'

const auth = useAuthStore();
const router = useRouter();
const loginModalComponent = ref(null); // Reference to the Login component

const templates = ref([]); // Store the templates fetched from the server

// Fetch the resume templates from the server
const fetchTemplates = () => {
    fetch('/resume-templates')
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                templates.value = data;  // Set the templates in the state
            } catch (e) {
                console.error('Not valid JSON:', e);
            }
        })
        .catch(error => {
            console.error('Network error:', error);
        });
};

// Check if the user is authenticated before proceeding to the editor
const checkAuthenticationAndProceed = (event, templateSlug) => {
    if (!auth.isAuthenticated) {
        event.preventDefault();  // Prevent the default action (navigating to the template)

        // Trigger the login modal using the Login component's method
        showLoginModal();
    } else {
        // If authenticated, proceed to the Resume Editor
        router.push(`/resume-editor/${templateSlug}`);
    }
};

// Trigger the login modal using the Login component's exposed method
const showLoginModal = () => {
    if (loginModalComponent.value) {
        loginModalComponent.value.show();
    }
};

fetchTemplates();  // Fetch templates when the component is mounted
</script>

<style scoped>
.active {
    background: linear-gradient(rgba(15, 54, 59, 1), rgba(14, 110, 122, 1)) !important;
    color: white !important;
}

.custom-boc-shadow {
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
</style>
