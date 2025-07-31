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
                <span class="editor-tip mt-2 mt-lg-0">A well-written cover letter increases your chances of getting
                  noticed!</span>
              </div>
            </div>
            <!-- Form -->
            <form @submit.prevent="handleNext">
              <div class="row">
                <template v-if="step === 1">
                  <h5 class="mb-3">Personal Details</h5>
                  <div class="col-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input class="form-custom" type="text" v-model="coverData.name" />
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label">Job Title</label>
                    <input class="form-custom" type="text" v-model="coverData.jobTitle" />
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label">Address</label>
                    <input class="form-custom" type="text" v-model="coverData.address" />
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-custom" v-model="coverData.email" type="email" />
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input class="form-custom" v-model="coverData.phone" type="text" />
                  </div>
                </template>
                <template v-else-if="step === 2">
                  <h5 class="mb-3">Employer Details</h5>
                  <div class="col-6 mb-3">
                    <label class="form-label">Company Name</label>
                    <input class="form-custom" type="text" v-model="coverData.company" />
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label">Hiring Manager Name</label>
                    <input class="form-custom" v-model="coverData.hiringManager" type="text" />
                  </div>
                </template>
                <template v-else-if="step === 3">
                  <h5 class="mb-3">Letter Details</h5>
                  <div class="col-12 mb-3">
                    <label class="form-label">Cover Letter</label>
                    <QuillEditor v-model="coverData.letter" :key="route.params.id + '-letter'" />
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
                <button v-else type="button" @click="saveCoverLetter" class="btn theme-btn btn-lg flex-grow-1">
                  Save Cover Letter
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- Right: Preview -->
        <div class="col-lg-6 editor-preview-bg">
          <div class="editor-preview-card">
            <!-- Zoom Controls -->
            <div class="zoom-controls">
              <button @click="zoomOut" class="zoom-btn" :disabled="zoomLevel <= 0.5">
                <i class="bi bi-dash-lg"></i>
              </button>
              <span class="zoom-level">{{ Math.round(zoomLevel * 100) }}%</span>
              <button @click="zoomIn" class="zoom-btn" :disabled="zoomLevel >= 2">
                <i class="bi bi-plus-lg"></i>
              </button>
              <button @click="resetZoom" class="zoom-btn reset-btn">
                <i class="bi bi-arrow-clockwise"></i>
              </button>
            </div>
            
            <div class="preview-scaler">
              <div class="cover-preview-content">
                <div v-if="!templateComponent" class="d-flex justify-content-center align-items-center"
                  style="height: 100%;">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>
                <div v-else class="preview-wrapper" :style="{ transform: `scale(${zoomLevel})` }">
                  <component v-if="templateComponent" :is="templateComponent" :cover="coverData" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import QuillEditor from '@/Components/form/QuillEditor.vue';

const route = useRoute();
const router = useRouter();
const coverId = route.params.id;
const coverData = ref({
  name: '',
  jobTitle: '',
  address: '',
  email: '',
  phone: '',
  company: '',
  hiringManager: '',
  letter: '',
  user_id: '',
  template: null,
  template_id: null,
});
const templateComponent = ref(null);
const step = ref(1);
const totalSteps = 3;
const stepTitles = [
  'Personal Details',
  'Employer Details',
  'Letter Details',
];
const currentStepTitle = computed(() => stepTitles[step.value - 1]);
const nextStepTitle = computed(() => stepTitles[step.value] || 'Finish');
const progressPercent = computed(() => Math.round((step.value - 1) / (totalSteps - 1) * 100));

const zoomLevel = ref(1);
const zoomStep = 0.1;
const minZoom = 0.5;
const maxZoom = 2;

const zoomIn = () => {
  if (zoomLevel.value < maxZoom) {
    zoomLevel.value = Math.min(maxZoom, zoomLevel.value + zoomStep);
  }
};

const zoomOut = () => {
  if (zoomLevel.value > minZoom) {
    zoomLevel.value = Math.max(minZoom, zoomLevel.value - zoomStep);
  }
};

const resetZoom = () => {
  zoomLevel.value = 1;
};

// Keyboard shortcuts for zoom
const handleKeydown = (event) => {
  if (event.ctrlKey || event.metaKey) {
    switch (event.key) {
      case '=':
      case '+':
        event.preventDefault();
        zoomIn();
        break;
      case '-':
        event.preventDefault();
        zoomOut();
        break;
      case '0':
        event.preventDefault();
        resetZoom();
        break;
    }
  }
};

const fetchCoverLetter = async () => {
  try {
    const { data } = await axios.get(`/api/cover-letters/${coverId}`);
    if (data.success && data.data) {
      Object.assign(coverData.value, data.data);
      if (data.data.template && data.data.template.component) {
        await loadTemplateByComponent(data.data.template.component);
      }
    } else {
      alert('Failed to load cover letter.');
      router.replace('/dashboard');
    }
  } catch (err) {
    alert('Cover letter not found.');
    router.replace('/dashboard');
  }
};

const loadTemplateByComponent = async (componentName) => {
  try {
    const module = await import(`@/Components/templates/cover-letter/${componentName}.vue`);
    templateComponent.value = module.default;
  } catch (error) {
    alert('Failed to load cover letter template.');
  }
};

onMounted(() => {
  fetchCoverLetter();
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
});

function handleNext() {
  if (step.value < totalSteps) {
    step.value++;
  }
}
function handlePrev() {
  if (step.value > 1) step.value--;
}

const saveCoverLetter = async () => {
  try {

    const response = await axios.put(`/api/cover-letter/${route.params.id}`, coverData.value);

    if (response.data.success) {
      router.replace(`/cover-letter/${coverId}/view`);
    } else {
      alert(response.data.message || 'Failed to update cover letter.');
    }
  } catch (err) {
    alert('An error occurred while updating.');
  }
};
</script>

<style scoped>
/* Copy all styles from CoverEditor.vue for consistency */
.editor-bg {
  background: #f7f9fb;
  height: 100vh;
  width: 100%;
}

.editor-form-card {
  height: 100vh;
  overflow-y: auto;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.editor-form-card::-webkit-scrollbar {
  display: none;
}

.editor-container {
  margin: 0 auto;
}

.editor-card {
  padding: 40px 32px 32px 32px;
  width: 100%;
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
  height: 100vh;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  position: relative;
}

/* Zoom Controls */
.zoom-controls {
  position: absolute;
  top: 20px;
  right: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.95);
  padding: 8px 12px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 10;
  backdrop-filter: blur(10px);
}

.zoom-btn {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  padding: 6px 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 32px;
  height: 32px;
}

.zoom-btn:hover:not(:disabled) {
  background: #e9ecef;
  border-color: #adb5bd;
}

.zoom-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.zoom-btn.reset-btn {
  background: #0e6e7a;
  color: white;
  border-color: #0e6e7a;
}

.zoom-btn.reset-btn:hover:not(:disabled) {
  background: #0a5a63;
}

.zoom-level {
  font-size: 14px;
  font-weight: 500;
  color: #495057;
  min-width: 50px;
  text-align: center;
}

.preview-scaler {
  width: 100%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.cover-preview-content {
  width: 100%;
  height: 100%;
  background: #fff;
  box-shadow: 0 4px 32px rgba(30, 37, 50, 0.08);
  border-radius: 18px;
  overflow: auto;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 20px;
}

.preview-wrapper {
  transform-origin: top center;
  transition: transform 0.2s ease;
  min-width: 100%;
  display: flex;
  justify-content: center;
}

.cover-preview-content::-webkit-scrollbar {
  width: 4px;
  background: transparent;
}

.cover-preview-content::-webkit-scrollbar-thumb {
  background: #cfd8dc;
  border-radius: 4px;
}

/* Responsive Design */
@media (max-width: 991.98px) {
  .editor-container {
    flex-direction: column;
  }
  
  .editor-form-card {
    height: auto;
    max-height: 50vh;
  }
  
  .editor-preview-bg {
    height: 50vh;
  }
  
  .editor-preview-card {
    height: 50vh;
  }
  
  .zoom-controls {
    top: 10px;
    right: 10px;
    padding: 6px 8px;
    gap: 4px;
  }
  
  .zoom-btn {
    min-width: 28px;
    height: 28px;
    padding: 4px 6px;
  }
  
  .zoom-level {
    font-size: 12px;
    min-width: 40px;
  }
  
  .cover-preview-content {
    padding: 15px;
  }
}

@media (max-width: 575.98px) {
  .editor-card {
    padding: 20px 16px;
  }
  
  .zoom-controls {
    top: 5px;
    right: 5px;
    padding: 4px 6px;
  }
  
  .zoom-btn {
    min-width: 24px;
    height: 24px;
    padding: 3px 4px;
  }
  
  .zoom-level {
    font-size: 11px;
    min-width: 35px;
  }
  
  .cover-preview-content {
    padding: 10px;
  }
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
</style>