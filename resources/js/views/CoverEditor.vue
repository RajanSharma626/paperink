<template>
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col"></div>
            <div class="col"><button @click.prevent="downloadPDF" class="btn btn-outline-primary mt-3">
                    Download Resume as PDF
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
                                    <input class="form-custom" v-model="coverStore.coverData['hiringManager']" type="text" />
                                </div>
                            </div>
                        </div>


                        <!-- Rich Text Area (Summary and other descriptions) -->
                        <div class="col-12 mb-3">
                            <h4>Letter Details</h4>
                            <QuillEditor v-model="coverStore.coverData['letter']" />
                        </div>
                    </div>

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
import html2pdf from 'html2pdf.js'

const route = useRoute()
const templateId = route.params.template
const coverStore = useCoverStore()
const templateComponent = ref(null)

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

function downloadPDF() {
    const element = document.getElementById('page') // 🔥 Your resume preview div
    if (!element) {
        alert('Cover Letter preview not found!')
        return
    }

    const opt = {
        margin: 0,
        filename: 'My_Cover_Letter.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    }

    html2pdf().from(element).set(opt).save()
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
