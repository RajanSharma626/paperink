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
            <div class="col-6 border-end p-5 overflow-auto">
                <form @submit.prevent="handleNext">
                    <div class="row">
                        <template v-for="(field, index) in currentFields" :key="index">

                            <!-- Text Input -->
                            <div v-if="field.type === 'text'" class="col-6 mb-3">
                                <label class="form-label">{{ field.label }}</label>
                                <input v-model="resumeStore.resumeData[field.field]" class="form-custom" />
                            </div>

                            <!-- Rich Text Area (Summary and other descriptions) -->
                            <div v-else-if="field.type === 'richtext'" class="col-12 mb-3">
                                <label class="form-label">{{ field.label }}</label>
                                <!-- <textarea v-model="resumeStore.resumeData[field.field]" class="form-custom">
                                    </textarea> -->
                                <QuillEditor v-model="resumeStore.resumeData[field.field]" />

                            </div>

                            <!-- Employment History -->
                            <div v-else-if="field.type === 'employment'" class="col-12 mb-3">
                                <div class="row">
                                    <div v-for="(job, jobIndex) in resumeStore.resumeData.employmentHistory"
                                        :key="jobIndex" class="col-12 mb-3 border p-3 rounded">
                                        <div class="row">
                                            <div class="col-6">
                                                <input v-model="job.jobTitle" class="form-custom mb-2"
                                                    placeholder="Job Title" />
                                            </div>
                                            <div class="col-6">
                                                <input v-model="job.company" class="form-custom mb-2"
                                                    placeholder="Company" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <input v-model="job.startDate" type="month" class="form-custom mb-2"
                                                    placeholder="Start Date" />
                                            </div>
                                            <div class="col-6">
                                                <input v-model="job.endDate" type="month" class="form-custom mb-2"
                                                    placeholder="End Date" />
                                            </div>
                                        </div>
                                        <input v-model="job.city" class="form-custom mb-2" placeholder="City" />
                                        <!-- <textarea v-model="job.description" class="form-custom"
                                            placeholder="Description"></textarea> -->
                                        <QuillEditor v-model="job.description" />
                                        <button @click.prevent="removeEmployment(jobIndex)"
                                            class="btn btn-danger btn-sm mt-2">
                                            <i class="bi bi-trash3"></i> Remove
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <a @click.prevent="addEmployment" href="#" class="theme-clr mt-2">+ Add one more
                                            employment</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Education History -->
                            <div v-else-if="field.type === 'education'" class="col-12 mb-3">
                                <div class="row">
                                    <div v-for="(edu, eduIndex) in resumeStore.resumeData.education" :key="eduIndex"
                                        class="col-12 mb-3 border p-3 rounded">
                                        <input v-model="edu.school" class="form-custom mb-2" placeholder="School" />
                                        <input v-model="edu.degree" class="form-custom mb-2" placeholder="Degree" />
                                        <div class="row">
                                            <div class="col-6">
                                                <input v-model="edu.startDate" type="month" class="form-custom mb-2"
                                                    placeholder="Start Date" />
                                            </div>
                                            <div class="col-6">
                                                <input v-model="edu.endDate" type="month" class="form-custom mb-2"
                                                    placeholder="End Date" />
                                            </div>
                                        </div>
                                        <input v-model="edu.city" class="form-custom mb-2" placeholder="City" />
                                        <!-- <textarea v-model="edu.description" class="form-custom"
                                            placeholder="Description"></textarea> -->
                                        <QuillEditor v-model="edu.description" />
                                        <button @click.prevent="removeEducation(eduIndex)"
                                            class="btn btn-danger btn-sm mt-2">
                                            Remove
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <a @click.prevent="addEducation" href="#" class="theme-clr mt-2">+ Add one more
                                            education</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Skills -->
                            <div v-else-if="field.type === 'skills'" class="col-12 mb-3">
                                <label>{{ field.label }}</label>
                                <div class="row">
                                    <div v-for="(skill, skillIndex) in resumeStore.resumeData.skills" :key="skillIndex"
                                        class="col-12 mb-3 border p-3 rounded">
                                        <input v-model="skill.skill" class="form-custom mb-2"
                                            placeholder="Skill Name" />
                                        <select v-model="skill.level" class="form-custom">
                                            <option value="">Select Level</option>
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Expert">Expert</option>
                                        </select>
                                        <button @click.prevent="removeSkill(skillIndex)"
                                            class="btn btn-danger btn-sm mt-2">
                                            Remove
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <button @click.prevent="addSkill" class="btn btn-primary mt-2">Add
                                            Skill</button>
                                    </div>
                                </div>
                            </div>

                        </template>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button v-if="step > 1" @click.prevent="handlePrev" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-short"></i> Previous
                        </button>
                        <button v-if="step < totalSteps" type="submit" class="btn theme-btn">
                            Next <i class="bi bi-arrow-right-short"></i>
                        </button>
                        <button v-else type="submit" class="btn btn-success">
                            Save Resume
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Side: Live Preview -->
            <div class="col-6 bg-light preview-wrapper">
                <div v-if="!templateComponent" class="d-flex justify-content-center align-items-center"
                    style="height: 100%;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div v-else class="preview-scale">
                    <component :is="templateComponent" :resume="resumeStore.resumeData" />
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useResumeStore } from '@/stores/resume'
import QuillEditor from '@/Components/form/QuillEditor.vue'
import html2pdf from 'html2pdf.js'

const route = useRoute()
const resumeStore = useResumeStore()
const templateComponent = ref(null)

const loadTemplate = async () => {
    try {
        const { data } = await axios.get(`/api/resume-template/${route.params.template}`)
        const componentName = data.component
        templateComponent.value = (await import(`@/Components/templates/${componentName}.vue`)).default
    } catch (error) {
        console.error('Failed to load template:', error)
    }
}

onMounted(loadTemplate)


// Stepper Logic
const step = ref(1)

const formSteps = [
    [ // Step 1
        { field: 'name', label: 'First Name', type: 'text' },
        { field: 'lastName', label: 'Last Name', type: 'text' },
        { field: 'jobTitle', label: 'Job Title', type: 'text' },
        { field: 'email', label: 'Email', type: 'text' },
        { field: 'phone', label: 'Phone', type: 'text' },
        { field: 'address', label: 'Address', type: 'text' },
        { field: 'city', label: 'City/State', type: 'text' },
        { field: 'postalCode', label: 'Postal Code', type: 'text' },
        { field: 'country', label: 'Country', type: 'text' },
        { field: 'summary', label: 'Professional Summary', type: 'richtext' },
    ],
    [{ field: 'employmentHistory', label: 'Employment History', type: 'employment' }],
    [{ field: 'education', label: 'Education', type: 'education' }],
    [{ field: 'skills', label: 'Skills', type: 'skills' }],
]

const currentFields = computed(() => formSteps[step.value - 1])
const totalSteps = computed(() => formSteps.length)

// Step Functions
function handleNext() {
    if (step.value < totalSteps.value) {
        step.value++
    } else {
        console.log('Save to backend:', resumeStore.resumeData)
    }
}

function handlePrev() {
    if (step.value > 1) step.value--
}

// Manage Employment, Education, Skills
function addEmployment() {
    resumeStore.resumeData.employmentHistory.push({ jobTitle: '', company: '', startDate: '', endDate: '', city: '', description: '' })
}
function removeEmployment(index) {
    resumeStore.resumeData.employmentHistory.splice(index, 1)
}
function addEducation() {
    resumeStore.resumeData.education.push({ school: '', degree: '', startDate: '', endDate: '', city: '', description: '' })
}
function removeEducation(index) {
    resumeStore.resumeData.education.splice(index, 1)
}
function addSkill() {
    resumeStore.resumeData.skills.push({ skill: '', level: '' })
}
function removeSkill(index) {
    resumeStore.resumeData.skills.splice(index, 1)
}

function downloadPDF() {
    const element = document.getElementById('page') // 🔥 Your resume preview div
    if (!element) {
        alert('Resume preview not found!')
        return
    }

    const opt = {
        margin: 0,
        filename: 'My_Resume.pdf',
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
