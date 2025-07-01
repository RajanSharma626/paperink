<template>
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col"></div>
            <div class="col">
                <button @click="downloadPdf" class="btn btn-outline-success">
                    Download PDF
                </button>

            </div>
        </div>
        <div class="row p-5">

            <!-- Left Side: Step Form -->
            <div class="col-6 border-end overflow-auto">
                <form>
                    <div class="row">
                        <h4 class="mb-4">Personal Detail</h4>
                        <div class="col-12 mb-3">
                            <div class="row">
                                <!-- Text Input -->
                                <div class="col-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input class="form-custom" type="text" v-model="coverStore.coverData['name']" />
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Job Title</label>
                                    <input class="form-custom" type="text" v-model="coverStore.coverData['jobTitle']" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input class="form-custom" type="text" v-model="coverStore.coverData['address']" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-custom" v-model="coverStore.coverData['email']" type="email" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input class="form-custom" v-model="coverStore.coverData['phone']" type="text" />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <h4 class="mb-4">Employer Details</h4>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Comapny Name</label>
                                    <input class="form-custom" type="text" v-model="coverStore.coverData['company']" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Hiring Manager Name</label>
                                    <input class="form-custom" v-model="coverStore.coverData['hiringManager']"
                                        type="text" />
                                </div>
                            </div>
                        </div>


                        <!-- Rich Text Area (Summary and other descriptions) -->
                        <div class="col-12 mb-3">
                            <h4>Letter Details</h4>
                            <QuillEditor v-model="coverStore.coverData['letter']" />
                        </div>
                    </div>

                    <button @click.prevent="saveCoverLetter" class="btn btn-success mt-3 ms-3">
                        Save Cover Letter
                    </button>


                </form>
            </div>

            <!-- Right Side: Live Preview -->
            <!-- Right Side: Live Preview -->
            <div class="col-6 bg-light preview-wrapper">
                <div v-if="!templateComponent" class="d-flex justify-content-center align-items-center"
                    style="height: 100%;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div v-else class="preview-scale">
                    <component :is="templateComponent" :cover="coverStore.coverData" />
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useCoverStore } from '@/stores/cover'
import QuillEditor from '@/Components/form/QuillEditor.vue'
import { useAuthStore } from "@/stores/auth";
const route = useRoute()
const templateId = route.params.template
const coverStore = useCoverStore()
const templateComponent = ref(null)

const authStore = useAuthStore();

const loadTemplate = async () => {
    try {
        const { data } = await axios.get(`/api/cover-template/${route.params.template}`)
        const componentName = data.component
        templateComponent.value = (await import(`@/Components/templates/cover-letter/${componentName}.vue`)).default
    } catch (error) {
        console.error('Failed to load template:', error)
    }
}

onMounted(loadTemplate)

async function downloadPdf() {
    try {
        // Show loading state
        const loadingBtn = document.querySelector('[data-loading]')
        if (loadingBtn) {
            loadingBtn.disabled = true
            loadingBtn.innerHTML = 'Generating PDF...'
        }

        const payload = {
            name: coverStore.coverData.name,
            jobTitle: coverStore.coverData.jobTitle,
            company: coverStore.coverData.company,
            hiringManager: coverStore.coverData.hiringManager,
            email: coverStore.coverData.email,
            phone: coverStore.coverData.phone,
            address: coverStore.coverData.address,
            letter: coverStore.coverData.letter,
        }

        console.log('Generating PDF with data:', payload)

        const response = await axios.post(
            `/api/cover-letters/${route.params.template}/pdf`,
            payload,
            {
                responseType: 'blob',
                headers: {
                    'Accept': 'application/pdf',
                    'Content-Type': 'application/json'
                },
                timeout: 60000 // 60 second timeout
            }
        )

        // Create download
        const blob = new Blob([response.data], { type: 'application/pdf' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `${coverStore.coverData.name || 'cover-letter'}-${new Date().toISOString().split('T')[0]}.pdf`
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        window.URL.revokeObjectURL(url)

        console.log('PDF downloaded successfully')

    } catch (error) {
        console.error('PDF generation failed:', error)

        let errorMessage = 'Failed to generate PDF. Please try again.'
        if (error.response?.status === 500) {
            errorMessage = 'Server error while generating PDF. Please check your data and try again.'
        } else if (error.code === 'ECONNABORTED') {
            errorMessage = 'PDF generation timed out. Please try again.'
        }

        alert(errorMessage)
    } finally {
        const loadingBtn = document.querySelector('[data-loading]')
        if (loadingBtn) {
            loadingBtn.disabled = false
            loadingBtn.innerHTML = 'Download PDF'
        }
    }
}

// Server-side PDF using your Vue component
async function downloadPdfServerSide() {
    const payload = {
        name: coverStore.coverData.name,
        jobTitle: coverStore.coverData.jobTitle,
        company: coverStore.coverData.company,
        hiringManager: coverStore.coverData.hiringManager,
        email: coverStore.coverData.email,
        phone: coverStore.coverData.phone,
        address: coverStore.coverData.address,
        letter: coverStore.coverData.letter,
    }

    const response = await axios.post(
        `/api/cover-letters/${route.params.template}/pdf`,
        payload,
        {
            responseType: 'blob',
            headers: { 'Accept': 'application/pdf' }
        }
    )

    // Download the PDF
    const blob = new Blob([response.data], { type: 'application/pdf' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `${coverStore.coverData.name || 'cover-letter'}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)
}

// Client-side PDF with improved quality
async function downloadPdfClientSide() {
    const element = document.querySelector('.preview-scale')

    // Temporarily remove transform for better rendering
    const originalTransform = element.style.transform
    element.style.transform = 'scale(1)'

    const opt = {
        margin: 0,
        filename: `${coverStore.coverData.name || 'cover-letter'}.pdf`,
        image: {
            type: 'jpeg',
            quality: 1,
            crossOrigin: 'anonymous'
        },
        html2canvas: {
            scale: 3, // Higher scale for better quality
            useCORS: true,
            letterRendering: true,
            allowTaint: false,
            backgroundColor: '#ffffff',
            removeContainer: true,
            onclone: (clonedDoc) => {
                // Ensure all styles are applied to cloned document
                const clonedElement = clonedDoc.querySelector('.preview-scale')
                if (clonedElement) {
                    clonedElement.style.transform = 'scale(1)'
                }
            }
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait',
            compress: true
        }
    }

    await html2pdf().set(opt).from(element).save()

    // Restore original transform
    element.style.transform = originalTransform
}




</script>

<style scoped>
label {
    display: flex;
    align-items: center;
    font-size: 14px;
    line-height: 20px;
    color: rgb(130, 139, 162);
    font-weight: 300;
}

.form-custom {
    border-radius: 3px;
    padding: 12px 16px;
    width: 100%;
    display: block;
    caret-color: #0e6e7a;
    background-color: rgb(239, 242, 249);
    outline: none;
    border: 0px;
    color: rgb(30, 37, 50);
    transition: color 0.1s;
}

.form-custom:focus {
    color: rgb(30, 37, 50);
    border-bottom: 2px solid #0e6e7a;
}


.preview-wrapper {
    padding: 10px;
    height: 100vh;
    background: #f8f9fa;
}

.preview-scale {
    transform: scale(0.7);
    /* Adjust scale to fit */
    transform-origin: top center;
}
</style>
