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
                    Welcome back, <span class="theme-clr">{{ auth.user?.name?.charAt(0).toUpperCase() +
                        auth.user?.name?.slice(1) }}</span>!
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

                            <!-- Loading state -->
                            <div v-if="loading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading your resumes...</p>
                            </div>

                            <div v-else-if="resumes.length === 0" class="text-center py-5 text-muted">
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

                                <!-- Resume Template Cards -->
                                <div v-for="resume in resumes" :key="resume.id" class="col-12 col-sm-6 col-lg-3">
                                    <router-link :to="`/resume/${resume.id}/view`" class="text-decoration-none">
                                        <div class="card h-100 border">
                                            <div class="card-body d-flex flex-column justify-content-between p-0">
                                                <div>
                                                    <img :src="resume.template.preview_img"
                                                        class="img-fluid rounded custom-boc-shadow"
                                                        alt="Template Preview" />
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>

                        <!-- Cover Letter Tab -->
                        <div :class="['tab-pane fade', { 'show active': activeTab === 'cover-letter' }]"
                            id="cover-letter" role="tabpanel">

                            <!-- Loading state -->
                            <div v-if="loading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading your cover letters...</p>
                            </div>

                            <div v-else-if="coverLetters.length === 0" class="text-center py-5 text-muted">
                                <h5>Cover Letter Templates</h5>
                                <p>Cover letter templates will be displayed here</p>
                            </div>

                            <div v-else class="row g-2">

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

                                <!-- Cover Letter Template Cards -->
                                <div v-for="coverLetter in coverLetters" :key="coverLetter.id"
                                    class="col-12 col-sm-6 col-lg-3">
                                    <router-link :to="`/cover-letter/${coverLetter.id}/view`"
                                        class="text-decoration-none">
                                        <div class="card h-100 border">
                                            <div class="card-body d-flex p-0 flex-column justify-content-between">
                                                <div>
                                                    <img :src="coverLetter.template.preview_img"
                                                        class="img-fluid rounded custom-boc-shadow"
                                                        alt="Template Preview" />
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
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
import { ref, onMounted, computed, nextTick } from 'vue'
import { useAuthStore } from '@/stores/auth'

const activeTab = ref('resume')
const loading = ref(false)
const error = ref(null)

const auth = useAuthStore()
const userInitial = computed(() => auth.user?.name?.charAt(0).toUpperCase() || 'U')

const tabs = [
    { id: 'resume', name: 'Resume' },
    { id: 'cover-letter', name: 'Cover Letter' }
]

const resumes = ref([]);
const coverLetters = ref([]);

// Improved error handling and retry logic
const makeApiRequest = async (url, data, retries = 3) => {
    for (let i = 0; i < retries; i++) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

            if (!csrfToken) {
                throw new Error('CSRF token not found')
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }

            const result = await response.json();
            return result;

        } catch (err) {
            console.error(`Request attempt ${i + 1} failed:`, err);

            if (i === retries - 1) {
                throw err; // Last retry failed
            }

            // Wait before retrying
            await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
        }
    }
};

// Fetch resumes with improved error handling
const fetchResume = async () => {
    const userId = auth.user?.id;
    if (!userId) {
        console.error('User ID not found');
        return;
    }

    try {
        loading.value = true;
        error.value = null;

        const data = await makeApiRequest('/api/my-resume', { user_id: userId });

        if (data.success) {
            resumes.value = Array.isArray(data.data) ? data.data : [];
            // console.log('Resume data loaded:', resumes.value.length, 'items');
        } else {
            console.error('API Error:', data.message);
            resumes.value = [];
            error.value = data.message || 'Failed to load resumes';
        }
    } catch (err) {
        console.error('Failed to fetch resumes:', err);
        resumes.value = [];
        error.value = 'Network error while loading resumes';
    } finally {
        loading.value = false;
    }
};

// Fetch cover letters with improved error handling
const fetchCover = async () => {
    const userId = auth.user?.id;
    if (!userId) {
        console.error('User ID not found');
        return;
    }

    try {
        loading.value = true;
        error.value = null;

        const data = await makeApiRequest('/api/my-cover', { user_id: userId });

        if (data.success) {
            coverLetters.value = Array.isArray(data.data) ? data.data : [];
            // console.log('Cover letter data loaded:', coverLetters.value.length, 'items');
        } else {
            console.error('API Error:', data.message);
            coverLetters.value = [];
            error.value = data.message || 'Failed to load cover letters';
        }
    } catch (err) {
        console.error('Failed to fetch cover letters:', err);
        coverLetters.value = [];
        error.value = 'Network error while loading cover letters';
    } finally {
        loading.value = false;
    }
};

// Load all data
const loadData = async () => {
    if (!auth.user?.id) {
        // console.log('Waiting for auth user...');
        return;
    }

    // console.log('Loading dashboard data for user:', auth.user.id);

    // Load both resumes and cover letters in parallel
    await Promise.all([
        fetchResume(),
        fetchCover()
    ]);
};

// Wait for auth to be ready, then load data
const initializeDashboard = async () => {
    // If auth store has a method to ensure it's ready, use it
    if (typeof auth.initialize === 'function') {
        await auth.initialize();
    }

    // Wait a tick to ensure reactivity is set up
    await nextTick();

    // If still no user, wait a bit more
    if (!auth.user?.id) {
        setTimeout(initializeDashboard, 100);
        return;
    }

    await loadData();
};

// Initialize on component mount
onMounted(() => {
    // console.log('Dashboard component mounted');
    initializeDashboard();
});

// Expose methods for potential external calls (debugging)
defineExpose({
    loadData,
    fetchResume,
    fetchCover
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

.bg-opacity-25 {
    --bs-bg-opacity: 0.25;
}

.document-bg {
    background-color: #eceaea;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>