<template>
    <div class="bg-light min-vh-100 p-4">
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4 mt-3">
                <div class="d-flex align-items-center me-2">
                    <span
                        class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                        style="width:40px;height:40px;font-weight:bold;">
                        {{ userInitial }}
                    </span>
                </div>
                <h3 class="fw-bold mb-0 text-dark">
                    Welcome back, <span class="theme-clr">{{ auth.user.name.charAt(0).toUpperCase() +
                        auth.user.name.slice(1) }}</span>!
                </h3>
            </div>

            <!-- Document Section -->
            <div class="card document-bg border-0 p-2">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold text-dark mb-4">Your Document</h3>

                    <!-- Bootstrap Tabs -->
                    <ul class="nav nav-tabs mb-4" id="documentTabs" role="tablist">
                        <li class="nav-item" role="presentation" v-for="tab in tabs" :key="tab.id">
                            <button :class="['nav-link', { active: activeTab === tab.id }]" :id="tab.id + '-tab'"
                                data-bs-toggle="tab" :data-bs-target="'#' + tab.id" type="button" role="tab"
                                @click="activeTab = tab.id">
                                {{ tab.name }}
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="documentTabsContent">
                        <!-- Resume Tab -->
                        <div :class="['tab-pane fade', { 'show active': activeTab === 'resume' }]" id="resume"
                            role="tabpanel">

                            <div v-if="resumes.length === 0" class="text-center py-5 text-muted">
                                <h5>Resume Templates</h5>
                                <p>Resume templates will be displayed here</p>
                            </div>
                            <div v-else class="row g-2">

                                <!-- New Resume Card -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <router-link to="/resume" class="text-decoration-none">
                                        <div class="card h-100 border-2 border-dashed text-center">
                                            <div
                                                class="card-body d-flex flex-column align-items-center justify-content-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3"
                                                    style="width: 48px; height: 48px;">
                                                    <svg class="text-secondary" width="24" height="24" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </div>
                                                <span class="text-secondary fw-medium">New Resume</span>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>

                                <!-- Resume Template 1 -->
                                <div v-for="resume in resumes" :key="resume.id" class="col-12 col-sm-6 col-lg-3">
                                    <div class="card h-100 border">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <img :src="resume.template.preview_img"
                                                    class="img-fluid rounded custom-boc-shadow"
                                                    alt="Template Preview" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cover Letter Tab -->
                        <div :class="['tab-pane fade', { 'show active': activeTab === 'cover-letter' }]"
                            id="cover-letter" role="tabpanel">
                            <div v-if="coverLetters.length === 0" class="text-center py-5 text-muted">
                                <h5>Cover Letter Templates</h5>
                                <p>Cover letter templates will be displayed here</p>
                            </div>

                            <div v-else class="row gap-2">

                                <!-- New Cover letter Card -->
                                 <div class="col-12 col-sm-6 col-lg-3">
                                    <router-link to="/cover-letter" class="text-decoration-none">
                                        <div class="card h-100 border-2 border-dashed text-center">
                                            <div
                                                class="card-body d-flex flex-column align-items-center justify-content-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3"
                                                    style="width: 48px; height: 48px;">
                                                    <svg class="text-secondary" width="24" height="24" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </div>
                                                <span class="text-secondary fw-medium">New Cover Letter</span>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>

                                <!-- coverLetter Template 1 -->
                                <div v-for="coverLetter in coverLetters" :key="coverLetter.id" class="col-12 col-sm-6 col-lg-3">
                                    <div class="card h-100 border">
                                        <div class="card-body d-flex p-0 flex-column justify-content-between">
                                            <div>
                                                <img :src="coverLetter.template.preview_img"
                                                    class="img-fluid rounded custom-boc-shadow"
                                                    alt="Template Preview" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Portfolio Tab -->
                        <div :class="['tab-pane fade', { 'show active': activeTab === 'portfolio' }]" id="portfolio"
                            role="tabpanel">
                            <div class="text-center py-5 text-muted">
                                <h5>Portfolio Templates</h5>
                                <p>Portfolio templates will be displayed here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref } from 'vue'

import { useAuthStore } from '@/stores/auth'

ref(() => {
    setTimeout(fetchResume, 100); // Small delay to ensure CSRF token is available
});

const activeTab = ref('resume')

const auth = useAuthStore()
const userInitial = ref(auth.user?.name?.charAt(0).toUpperCase() || 'U')

const tabs = [
    { id: 'resume', name: 'Resume' },
    { id: 'cover-letter', name: 'Cover Letter' }
]

const resumes = ref([]);
const coverLetters = ref([]);

// Fetch the resume templates from the server
const fetchResume = () => {
    const userId = auth.user?.id;
    if (!userId) {
        console.error('User ID not found');
        return;
    }

    fetch('/my-resume', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ user_id: userId })
    })
        .then(response => response.json()) // Parse as JSON directly
        .then(data => {
            if (data.success) {
                resumes.value = data.data;  // Access the 'data' property from your API response
                console.log('Resume data:', resumes.value);
            } else {
                console.error('API Error:', data.message);
                resumes.value = []; // Ensure empty array on error
            }
        })
        .catch(error => {
            console.error('Network error:', error);
            resumes.value = []; // Ensure empty array on error
        });
};

// Fetch the resume templates from the server
const fetchCover = () => {
    const userId = auth.user?.id;
    if (!userId) {
        console.error('User ID not found');
        return;
    }

    fetch('/my-cover', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ user_id: userId })
    })
        .then(response => response.json()) // Parse as JSON directly
        .then(data => {
            if (data.success) {
                coverLetters.value = data.data;  // Access the 'data' property from your API response
                console.log('Cover data:', coverLetters.value);
            } else {
                console.error('API Error:', data.message);
                coverLetters.value = []; // Ensure empty array on error
            }
        })
        .catch(error => {
            console.error('Network error:', error);
            coverLetters.value = []; // Ensure empty array on error
        });
};

import { onMounted } from 'vue'

onMounted(() => {
    fetchResume();
    fetchCover();
});

</script>



<style scoped>
/* Custom Bootstrap overrides */
.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    background: none;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background: none;
    border-bottom-color: #212529;
    color: #212529;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: transparent;
    color: #495057;
}

/* .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
} */

.btn:hover {
    transform: scale(1.05);
    transition: transform 0.1s ease-in-out;
}

.border-dashed {
    border-style: dashed !important;
}

.border-dashed:hover {
    border-color: #adb5bd !important;
    cursor: pointer;
}

.min-vh-100 {
    min-height: 100vh;
}

/* Badge opacity utilities for better color contrast */
.bg-opacity-25 {
    --bs-bg-opacity: 0.25;
}

.document-bg {
    background-color: #eceaea;
}
</style>