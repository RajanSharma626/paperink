<template>
  <div class="editor-bg">
    <div class="container-fluid editor-container">
      <div class="row">
        <!-- Left: Form Card -->
        <div class="col-lg-6 p-4 border-end editor-form-card">
          <div class="editor-card w-100">
            <!-- Progress Bar & Header -->
            <div class="editor-header mb-4">
              <div class="progress mb-2" style="height: 6px;">
                <div class="progress-bar" :style="{ width: progressPercent + '%' }"></div>
              </div>
              <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-0">{{ currentStepTitle }}</h4>
                <span class="editor-tip mt-2 mt-lg-0">Users who added phone number and email received 64% more positive
                  feedback from recruiters.</span>
              </div>
            </div>
            <!-- Form -->
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
                    <QuillEditor v-model="resumeStore.resumeData[field.field]" />
                  </div>
                  <!-- Employment History -->
                  <div v-else-if="field.type === 'employment'" class="col-12 mb-3">
                    <div class="row">
                      <div v-for="(job, jobIndex) in resumeStore.resumeData.employmentHistory" :key="jobIndex"
                        class="col-12 mb-3 border p-3 rounded">
                        <div class="row">
                          <div class="col-6">
                            <input v-model="job.jobTitle" class="form-custom mb-2" placeholder="Job Title" />
                          </div>
                          <div class="col-6">
                            <input v-model="job.company" class="form-custom mb-2" placeholder="Company" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <input v-model="job.startDate" type="month" class="form-custom mb-2"
                              placeholder="Start Date" />
                          </div>
                          <div class="col-6">
                            <input v-model="job.endDate" type="month" class="form-custom mb-2" placeholder="End Date" />
                          </div>
                        </div>
                        <input v-model="job.city" class="form-custom mb-2" placeholder="City" />
                        <QuillEditor v-model="job.description" />
                        <button @click.prevent="removeEmployment(jobIndex)" class="btn btn-danger btn-sm mt-2">
                          <i class="bi bi-trash3"></i> Remove
                        </button>
                      </div>
                      <div class="col-12">
                        <a @click.prevent="addEmployment" href="#" class="theme-clr mt-2 add-field-btn">+ Add one more
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
                            <input v-model="edu.endDate" type="month" class="form-custom mb-2" placeholder="End Date" />
                          </div>
                        </div>
                        <input v-model="edu.city" class="form-custom mb-2" placeholder="City" />
                        <QuillEditor v-model="edu.description" />
                        <button @click.prevent="removeEducation(eduIndex)" class="btn btn-danger btn-sm mt-2">
                          Remove
                        </button>
                      </div>
                      <div class="col-12">
                        <a @click.prevent="addEducation" href="#" class="theme-clr mt-2 add-field-btn">+ Add one more
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
                        <input v-model="skill.skill" class="form-custom mb-2" placeholder="Skill Name" />
                        <select v-model="skill.level" class="form-custom">
                          <option value="">Select Level</option>
                          <option value="Beginner">Beginner</option>
                          <option value="Intermediate">Intermediate</option>
                          <option value="Expert">Expert</option>
                        </select>
                        <button @click.prevent="removeSkill(skillIndex)" class="btn btn-danger btn-sm mt-2">
                          Remove
                        </button>
                      </div>
                      <div class="col-12">
                        <button @click.prevent="addSkill" class="btn btn-primary mt-2 add-field-btn">Add Skill</button>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
              <!-- Navigation Button -->
              <div class="d-flex justify-content-between mt-4 gap-3">
                <button v-if="step > 1" @click.prevent="handlePrev" type="button"
                  class="btn btn-secondary btn-lg flex-grow-1">
                  <i class="bi bi-arrow-left-short"></i> Back
                </button>
                <button v-if="step < totalSteps" type="submit" class="btn theme-btn btn-lg flex-grow-1">
                  Next: {{ nextStepTitle }}
                </button>
                <button v-else type="submit" class="btn theme-btn btn-lg flex-grow-1" :disabled="isGeneratingPDF">
                  <span v-if="isGeneratingPDF" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  {{ isGeneratingPDF ? 'Saving Resume...' : 'Save Resume' }}
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- Right: Preview -->
        <div class="col-lg-6 editor-preview-bg">
          <div class="editor-preview-card">
            <div v-if="!templateComponent" class="d-flex justify-content-center align-items-center"
              style="height: 100%;">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else>
              <component 
                ref="resumePreview"
                :is="templateComponent" 
                :resume="resumeStore.resumeData"
                class="resume-preview-component"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay for Preview Generation -->
    <div v-if="isGeneratingPDF && showPreviewLoading" class="preview-loading-overlay">
      <div class="loading-content">
        <div class="spinner-border text-primary mb-3" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <h5>Generating Resume Preview...</h5>
        <p class="text-muted">Please wait while we create your resume preview image</p>
        <div class="progress mt-3" style="width: 300px;">
          <div class="progress-bar progress-bar-animated" style="width: 75%"></div>
        </div>
      </div>
    </div>

    <!-- Hidden Preview Container for Image Generation -->
    <div ref="hiddenPreviewContainer" class="hidden-preview-container" v-show="false">
      <component 
        v-if="templateComponent && isGeneratingPDF"
        :is="templateComponent" 
        :resume="resumeStore.resumeData"
        class="hidden-resume-preview"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import html2canvas from 'html2canvas'
import { useResumeStore } from '@/stores/resume'
import QuillEditor from '@/Components/form/QuillEditor.vue'

const route = useRoute()
const router = useRouter()
const resumeStore = useResumeStore()
const templateComponent = ref(null)
const isGeneratingPDF = ref(false)
const showPreviewLoading = ref(false)
const resumePreview = ref(null)
const hiddenPreviewContainer = ref(null)

const loadTemplate = async () => {
  try {
    const response = await axios.get(`/api/resume-template/${route.params.template}`)
    const componentName = response.data.component
    const module = await import(`@/components/templates/${componentName}.vue`)
    templateComponent.value = module.default
  } catch (error) {
    console.error('Failed to load template:', error)
    alert('Failed to load resume template. Please try again.')
  }
}

onMounted(loadTemplate)

// Stepper Logic
const step = ref(1)

const formSteps = [
  [
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

const stepTitles = [
  'Personal details',
  'Employment History',
  'Education',
  'Skills'
]
const currentStepTitle = computed(() => stepTitles[step.value - 1])
const nextStepTitle = computed(() => stepTitles[step.value] || 'Finish')
const currentFields = computed(() => formSteps[step.value - 1])
const totalSteps = computed(() => formSteps.length)
const progressPercent = computed(() => Math.round((step.value - 1) / (totalSteps.value - 1) * 100))

// Generate Preview Image
const generatePreviewImage = async () => {
  try {
    showPreviewLoading.value = true
    
    // Wait for DOM to update
    await nextTick()
    
    // Give additional time for fonts and styles to load
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    const canvas = await html2canvas(hiddenPreviewContainer.value, {
      width: 794, // A4 width at 96 DPI
      height: 1123, // A4 height at 96 DPI
      scale: 2, // Higher resolution
      useCORS: true,
      allowTaint: false,
      backgroundColor: '#ffffff',
      removeContainer: false,
      imageTimeout: 15000,
      logging: false,
      letterRendering: true,
      foreignObjectRendering: false,
      onclone: (clonedDoc) => {
        // Apply consistent styles to cloned document
        const style = clonedDoc.createElement('style')
        style.textContent = `
          * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
          }
          .hidden-resume-preview {
            width: 794px !important;
            min-height: 1123px !important;
            padding: 40px !important;
            background: white !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            color: #333 !important;
            line-height: 1.6 !important;
            box-sizing: border-box !important;
            margin: 0 !important;
            transform: none !important;
            zoom: 1 !important;
          }
          .hidden-resume-preview * {
            box-sizing: border-box !important;
          }
          /* Remove any animations or transitions */
          .hidden-resume-preview *, 
          .hidden-resume-preview *::before, 
          .hidden-resume-preview *::after {
            animation-duration: 0s !important;
            animation-delay: 0s !important;
            transition-duration: 0s !important;
            transition-delay: 0s !important;
          }
        `
        clonedDoc.head.appendChild(style)
        
        // Remove any problematic elements
        const problematicElements = clonedDoc.querySelectorAll('script, noscript, iframe, object, embed')
        problematicElements.forEach(el => el.remove())
      }
    })
    
    // Convert canvas to base64
    const imageData = canvas.toDataURL('image/png', 0.95)
    
    showPreviewLoading.value = false
    return imageData
    
  } catch (error) {
    console.error('Error generating preview:', error)
    showPreviewLoading.value = false
    throw error
  }
}

// Step Functions
async function handleNext() {
  if (step.value < totalSteps.value) {
    step.value++
  } else {
    try {
      isGeneratingPDF.value = true
      
      // Generate preview image first
      let previewImageData = null
      try {
        previewImageData = await generatePreviewImage()
        console.log('Preview image generated successfully')
      } catch (error) {
        console.error('Failed to generate preview image:', error)
        // Continue without preview image
      }
      
      // Save resume with preview image
      const savedResume = await resumeStore.saveResume(
        resumeStore.resumeData, 
        route.params.template, 
        previewImageData
      )

      if (savedResume.success) {
        const resumeId = savedResume.resumeId
        
        // Redirect to full view page and replace history so user can't go back
        router.replace(`/resume/${resumeId}/view`)
      } else {
        alert(savedResume.message || 'Failed to save resume')
      }
    } catch (error) {
      console.error('Failed to save resume:', error)
      alert('An unexpected error occurred. Please try again.')
    } finally {
      isGeneratingPDF.value = false
    }
  }
}

function handlePrev() {
  if (step.value > 1) step.value--
}

watch(step, (newStep) => {
  // Employment step (step 2, index 1)
  if (newStep === 2 && resumeStore.resumeData.employmentHistory.length === 0) {
    addEmployment()
  }
  // Education step (step 3, index 2)
  if (newStep === 3 && resumeStore.resumeData.education.length === 0) {
    addEducation()
  }
  // Skills step (step 4, index 3)
  if (newStep === 4 && resumeStore.resumeData.skills.length === 0) {
    addSkill()
  }
})

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
  resumeStore.resumeData.skills.push({ skill: '', level: 'Beginner' })
}
function removeSkill(index) {
  resumeStore.resumeData.skills.splice(index, 1)
}
</script>

<style scoped>
.editor-bg {
  background: #f7f9fb;
  height: 100vh;
  width: 100%;
}

.editor-form-card {
  height: 100vh;
  overflow-y: auto;
  scrollbar-width: none;
  /* Firefox */
  -ms-overflow-style: none;
  /* IE and Edge */
}

.editor-form-card::-webkit-scrollbar {
  display: none;
  /* Chrome, Safari, Opera */
}

.editor-container {
  /* max-width: 1600px; */
  margin: 0 auto;
}

.editor-card {
  padding: 40px 32px 32px 32px;
  width: 100%;
  /* max-width: 600px; */
}

.editor-header h4 {
  font-weight: 700;
  font-size: 1.5rem;
}

.editor-tip {
  color: #6c757d;
  font-size: 0.95rem;
}

.progress {
  background: #e9ecef;
  border-radius: 3px;
}

.progress-bar {
  background: #0e6e7a;
  transition: width 0.3s;
}

.editor-preview-card {
  /* background: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 32px rgba(30, 37, 50, 0.08); */
  padding: 40px;
  width: 100%;
  height: 100vh;
  overflow-y: auto;
  scrollbar-width: thin;
  /* Firefox */
  -ms-overflow-style: auto;
  /* IE and Edge */
}

.editor-preview-card::-webkit-scrollbar {
  width: 4px;
  /* Thin scrollbar for Chrome, Safari, Opera */
  background: transparent;
}

.editor-preview-card::-webkit-scrollbar-thumb {
  background: #cfd8dc;
  border-radius: 4px;
}

.btn-lg {
  font-size: 1.15rem;
  padding: 14px 0;
  border-radius: 8px;
}

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

.add-field-btn {
  font-size: 1rem;
  color: #0e6e7a;
  background: none;
  border: none;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
}

/* Preview Loading Overlay */
.preview-loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.95);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.loading-content {
  text-align: center;
  padding: 2rem;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Hidden Preview Container */
.hidden-preview-container {
  position: fixed;
  top: -99999px;
  left: -99999px;
  z-index: -1;
  pointer-events: none;
  width: 794px;
  min-height: 1123px;
  background: white;
  overflow: hidden;
}

:deep(.hidden-resume-preview) {
  width: 794px !important;
  min-height: 1123px !important;
  padding: 40px !important;
  background: white !important;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
  color: #333 !important;
  line-height: 1.6 !important;
  box-sizing: border-box !important;
  margin: 0 !important;
  transform: none !important;
  zoom: 1 !important;
  font-size: 14px !important;
}

/* Override any template styles that might interfere */
:deep(.hidden-resume-preview *) {
  box-sizing: border-box !important;
}

/* Remove any animations for clean capture */
:deep(.hidden-resume-preview *), 
:deep(.hidden-resume-preview *::before), 
:deep(.hidden-resume-preview *::after) {
  animation: none !important;
  transition: none !important;
}

/* Ensure proper font rendering */
:deep(.hidden-resume-preview) {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
}
</style>