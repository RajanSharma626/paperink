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
                                    <div class="position-relative resume-card-group">
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
                                        <!-- Hover Actions Overlay -->
                                        <transition name="fade-slide">
                                            <div v-if="true"
                                                class="resume-hover-overlay d-flex flex-column align-items-center justify-content-center">
                                                <div class="d-flex flex-row gap-2 w-100 justify-content-center mb-2">

                                                    <router-link :to="`/resume/${resume.id}/view`"
                                                        class="dashboard-action-btn view nav-link" title="View Resume">
                                                        <i class="bi bi-eye me-1"></i> View
                                                    </router-link>

                                                    <router-link :to="`/resume/${resume.id}/edit`"
                                                        class="dashboard-action-btn edit nav-link" title="Edit Resume">
                                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                                    </router-link>

                                                </div>
                                                <div class="d-flex flex-row gap-2 w-100 justify-content-center mb-2">
                                                    <button @click="downloadResumePdf(resume)"
                                                        :disabled="downloadingResumeId === resume.id"
                                                        class="dashboard-action-btn download" title="Download PDF">
                                                        <span v-if="downloadingResumeId === resume.id"
                                                            class="spinner-border spinner-border-sm me-1 fs-6"></span>
                                                        <i v-else class="bi bi-download me-1"></i>
                                                        <span
                                                            v-if="downloadingResumeId === resume.id">Downloading...</span>
                                                        <span v-else>Download</span>
                                                    </button>


                                                    <button @click="deleteResume(resume)"
                                                        :disabled="deletingResumeId === resume.id"
                                                        class="dashboard-action-btn delete" title="Delete Resume">
                                                        <span v-if="deletingResumeId === resume.id"
                                                            class="spinner-border spinner-border-sm me-1 fs-6"></span>
                                                        <i v-else class="bi bi-trash me-1"></i>
                                                        <span v-if="deletingResumeId === resume.id">Deleting...</span>
                                                        <span v-else>Delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </transition>
                                        <!-- Hidden Preview for PDF Generation -->
                                        <div v-if="pdfResumeId === resume.id" style="display:none">
                                            <component ref="hiddenResumePreview" :is="hiddenTemplateComponent"
                                                :resume="resume" class="hidden-resume-preview" />
                                        </div>
                                    </div>
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
                                    <div class="position-relative resume-card-group">
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
                                        <!-- Hover Actions Overlay -->
                                        <transition name="fade-slide">
                                            <div v-if="true"
                                                class="resume-hover-overlay d-flex flex-column align-items-center justify-content-center">
                                                <div class="d-flex flex-row gap-2 w-100 justify-content-center mb-2">

                                                    <router-link :to="`/cover-letter/${coverLetter.id}/view`"
                                                        class="dashboard-action-btn view nav-link"
                                                        title="View Cover Letter">
                                                        <i class="bi bi-eye me-1"></i> View
                                                    </router-link>

                                                    <router-link :to="`/cover-letter/${coverLetter.id}/edit`"
                                                        class="dashboard-action-btn edit nav-link"
                                                        title="Edit Cover Letter">
                                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                                    </router-link>
                                                </div>
                                                <div class="d-flex flex-row gap-2 w-100 justify-content-center mb-2">
                                                    <button @click="downloadCoverPdf(coverLetter)"
                                                        :disabled="downloadingCoverId === coverLetter.id"
                                                        class="dashboard-action-btn download" title="Download PDF">
                                                        <span v-if="downloadingCoverId === coverLetter.id"
                                                            class="spinner-border spinner-border-sm me-1 fs-6"></span>
                                                        <i v-else class="bi bi-download me-1"></i>
                                                        <span
                                                            v-if="downloadingCoverId === coverLetter.id">Downloading...</span>
                                                        <span v-else>Download</span>
                                                    </button>
                                                    <button @click="deleteCoverLetter(coverLetter)"
                                                        :disabled="deletingCoverId === coverLetter.id"
                                                        class="dashboard-action-btn delete" title="Delete Cover Letter">
                                                        <span v-if="deletingCoverId === coverLetter.id"
                                                            class="spinner-border spinner-border-sm me-1 fs-6"></span>
                                                        <i v-else class="bi bi-trash me-1"></i>
                                                        <span
                                                            v-if="deletingCoverId === coverLetter.id">Deleting...</span>
                                                        <span v-else>Delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </transition>
                                        <!-- Hidden Preview for PDF Generation (if needed) -->
                                        <div v-if="pdfCoverId === coverLetter.id" style="display:none">
                                            <component ref="hiddenCoverPreview" :is="hiddenCoverTemplateComponent"
                                                :cover="coverLetter" class="hidden-cover-preview" />
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
const pdfResumeId = ref(null);
const hiddenResumePreview = ref(null);
const hiddenTemplateComponent = ref(null);
const downloadingResumeId = ref(null);
const templateComponentCache = {};
const getTemplateComponent = (componentName) => {
    if (!componentName) return null;
    if (templateComponentCache[componentName]) return templateComponentCache[componentName];
    // Dynamic import
    const comp = () => import(`@/components/templates/${componentName}.vue`);
    templateComponentCache[componentName] = comp;
    return comp;
};

const pdfCoverId = ref(null);
const hiddenCoverPreview = ref(null);
const hiddenCoverTemplateComponent = ref(null);
const downloadingCoverId = ref(null);
const deletingResumeId = ref(null);
const deletingCoverId = ref(null);

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

// Add download handler
const downloadResumePdf = async (resume) => {
    downloadingResumeId.value = resume.id;
    // Dynamically import the template component
    try {
        const module = await import(`@/components/templates/${resume.template.component}.vue`);
        hiddenTemplateComponent.value = module.default;
    } catch (e) {
        alert('Could not load resume template for PDF.');
        downloadingResumeId.value = null;
        return;
    }
    pdfResumeId.value = resume.id;
    await nextTick();
    // Wait for DOM to render hidden preview (longer delay for async component)
    await new Promise((resolve) => setTimeout(resolve, 800));
    const previewEl = hiddenResumePreview.value?.$el || document.querySelector('.hidden-resume-preview');
    if (!previewEl) {
        alert('Could not render resume preview for PDF.');
        pdfResumeId.value = null;
        downloadingResumeId.value = null;
        return;
    }
    const htmlContent = previewEl.outerHTML;
    const styles = getAllStyles();
    try {
        const response = await fetch('/api/generate-pdf', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf',
            },
            body: JSON.stringify({
                html: htmlContent,
                styles,
                resume_id: resume.id,
                resume_name: resume.name || 'Resume',
                options: { format: 'A4' },
            }),
        });
        if (!response.ok) throw new Error('Failed to generate PDF');
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${resume.name || 'Resume'}_${new Date().toISOString().slice(0, 10)}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
    } catch (err) {
        alert('Could not download PDF.');
    } finally {
        pdfResumeId.value = null;
        hiddenTemplateComponent.value = null;
        downloadingResumeId.value = null;
    }
};

const downloadCoverPdf = async (coverLetter) => {
    downloadingCoverId.value = coverLetter.id;
    try {
        const module = await import(`@/components/templates/cover-letter/${coverLetter.template.component}.vue`);
        hiddenCoverTemplateComponent.value = module.default;
    } catch (e) {
        alert('Could not load cover letter template for PDF.');
        downloadingCoverId.value = null;
        return;
    }
    pdfCoverId.value = coverLetter.id;
    await nextTick();
    await new Promise((resolve) => setTimeout(resolve, 800));
    const previewEl = hiddenCoverPreview.value?.$el || document.querySelector('.hidden-cover-preview');
    if (!previewEl) {
        alert('Could not render cover letter preview for PDF.');
        pdfCoverId.value = null;
        downloadingCoverId.value = null;
        return;
    }
    const htmlContent = previewEl.outerHTML;
    const styles = getAllStyles();
    try {
        const response = await fetch('/api/cover-letter/generate-pdf', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf',
            },
            body: JSON.stringify({
                html: htmlContent,
                styles,
                cover_id: coverLetter.id,
                cover_name: coverLetter.name || 'CoverLetter',
                options: { format: 'A4' },
            }),
        });
        if (!response.ok) throw new Error('Failed to generate PDF');
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${coverLetter.name || 'CoverLetter'}_${new Date().toISOString().slice(0, 10)}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
    } catch (err) {
        alert('Could not download PDF.');
    } finally {
        pdfCoverId.value = null;
        hiddenCoverTemplateComponent.value = null;
        downloadingCoverId.value = null;
    }
};

const deleteResume = async (resume) => {
    if (!confirm(`Are you sure you want to delete "${resume.name}"? This action cannot be undone.`)) {
        return;
    }

    deletingResumeId.value = resume.id;
    try {
        const response = await fetch(`/api/resumes/${resume.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            // Remove from local array
            resumes.value = resumes.value.filter(r => r.id !== resume.id);
            alert('Resume deleted successfully!');
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to delete resume.');
        }
    } catch (err) {
        console.error('Delete resume error:', err);
        alert('An error occurred while deleting the resume.');
    } finally {
        deletingResumeId.value = null;
    }
};

const deleteCoverLetter = async (coverLetter) => {
    if (!confirm(`Are you sure you want to delete "${coverLetter.name}"? This action cannot be undone.`)) {
        return;
    }

    deletingCoverId.value = coverLetter.id;
    try {
        const response = await fetch(`/api/cover-letter/${coverLetter.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            // Remove from local array
            coverLetters.value = coverLetters.value.filter(c => c.id !== coverLetter.id);
            alert('Cover letter deleted successfully!');
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to delete cover letter.');
        }
    } catch (err) {
        console.error('Delete cover letter error:', err);
        alert('An error occurred while deleting the cover letter.');
    } finally {
        deletingCoverId.value = null;
    }
};

function getAllStyles() {
    let styles = '';
    const inlineStyles = document.querySelector('style');
    if (inlineStyles) styles += inlineStyles.innerHTML + '\n';
    const styleSheets = document.styleSheets;
    for (let i = 0; i < styleSheets.length; i++) {
        try {
            const rules = styleSheets[i].cssRules || styleSheets[i].rules;
            if (rules) {
                for (let j = 0; j < rules.length; j++) {
                    styles += rules[j].cssText + '\n';
                }
            }
        } catch (e) {
            // Cross-origin stylesheets
        }
    }
    return styles;
}
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
    width: 1rem;
    height: 1rem;
}

.resume-card-group {
    position: relative;
    overflow: visible;
}

.resume-hover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(44, 62, 80, 0.82);
    opacity: 0;
    transition: opacity 0.25s, transform 0.25s;
    z-index: 2;
    color: #fff;
    border-radius: 0.5rem;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    transform: translateY(20px);
}

.resume-card-group:hover .resume-hover-overlay {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}

.dashboard-action-btn {
    min-width: 120px;
    justify-content: center;
    border-radius: 2rem;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.65rem 1.5rem;
    display: flex;
    align-items: center;
    border: none;
    outline: none;
    box-shadow: 0 2px 8px rgba(44, 62, 80, 0.08);
    background: #fff;
    color: #2c3e50;
    transition: background 0.18s, color 0.18s, transform 0.18s, box-shadow 0.18s;
    cursor: pointer;
    position: relative;
    margin-bottom: 0;
}

.dashboard-action-btn.edit {
    background: #e0e7ff;
    color: #4f46e5;
}

.dashboard-action-btn.edit:hover,
.dashboard-action-btn.edit:focus {
    background: #c7d2fe;
    color: #3730a3;
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(79, 70, 229, 0.12);
}

.dashboard-action-btn.view {
    background: #f1f5f9;
    color: #0e7490;
}

.dashboard-action-btn.view:hover,
.dashboard-action-btn.view:focus {
    background: #bae6fd;
    color: #0369a1;
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(14, 116, 144, 0.12);
}

.dashboard-action-btn.download {
    background: #e0f2fe;
    color: #0284c7;
    margin-top: 0.5rem;
}

.dashboard-action-btn.download:hover,
.dashboard-action-btn.download:focus {
    background: #bae6fd;
    color: #0369a1;
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(2, 132, 199, 0.12);
}

.dashboard-action-btn.delete {
    background: #ffe0e0;
    color: #e74c3c;
    margin-top: 0.5rem;
}

.dashboard-action-btn.delete:hover,
.dashboard-action-btn.delete:focus {
    background: #ffc7c7;
    color: #c0392b;
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(231, 76, 60, 0.12);
}

.dashboard-action-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: opacity 0.25s, transform 0.25s;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>