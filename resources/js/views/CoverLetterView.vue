<template>
  <div class="container-fluid p-0">
    <!-- Loading State -->
    <div v-if="loading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="alert alert-danger m-5">
      {{ error }}
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- PDF Controls -->
      <div class="pdf-controls p-3 mb-4 bg-light sticky-top">
        <div class="d-flex justify-content-end">
          <button class="btn btn-primary me-2" @click="generatePDF" :disabled="generatingPDF">
            <i class="fas fa-download me-2"></i>
            {{ generatingPDF ? 'Generating PDF...' : 'Download PDF' }}
          </button>
        </div>
      </div>

      <!-- Cover Letter Content Container -->
      <div class="row justify-content-center p-3">
        <div class="col-lg-6 col-md-12 mb-4">
          <h5 class="mb-3">Select Template</h5>
          <div class="row py-3">
            <div v-for="template in templates" :key="template.id" class="col-lg-4 col-md-6 col-6 mb-3">
              <a href="javascript:void(0)" @click="updateTemp(template.id)">
                <img :src="template.preview_img" class="img-fluid rounded custom-boc-shadow tempImg"
                  :class="{ 'selected-temp': template.id == template_id }" alt="Template Preview">
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12">
          <div ref="coverContent" class="resume-container" style="width: 210mm; min-height: 297mm;">
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
            
            <div class="preview-wrapper" :style="{ transform: `scale(${zoomLevel})` }">
              <component v-if="templateComponent" :is="templateComponent" :cover="cover" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const coverId = route.params.id

// Reactive state
const cover = ref(null)
const templateComponent = ref(null)
const loading = ref(true)
const error = ref(null)
const coverContent = ref(null)
const generatingPDF = ref(false)

const templates = ref([]);
let template_id = ref(null)

// Zoom state
const zoomLevel = ref(1)

// Fetch cover letter data from API
const fetchCover = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await axios.get(`/api/cover-letters/${coverId}`)
    cover.value = response.data.data || response.data

    if (!cover.value) {
      throw new Error('No cover letter data received')
    }

    template_id = cover.value.template_id

    // Dynamically load the template component
    let templateName = null
    if (cover.value.template && cover.value.template.component) {
      templateName = cover.value.template.component
    } else if (cover.value.template_id) {
      // fallback: fetch template by id
      const tpl = await axios.get(`/api/cover-template/${cover.value.template_id}`)
      templateName = tpl.data.component
    }

    if (!templateName) {
      throw new Error('No template specified for this cover letter')
    }

    const module = await import(`@/Components/templates/cover-letter/${templateName}.vue`)
    templateComponent.value = module.default

  } catch (err) {
    console.error('Error loading cover letter:', err)
    error.value = err.response?.data?.message || err.message || 'Failed to load cover letter'
  } finally {
    loading.value = false
  }
}

// Generate PDF using Laravel backend
const generatePDF = async () => {
  generatingPDF.value = true

  try {
    // Get the complete HTML content
    const htmlContent = coverContent.value.outerHTML

    // Get all CSS styles
    const styles = getAllStyles()

    // Prepare data for Laravel backend
    const requestData = {
      html: htmlContent,
      styles: styles,
      cover_id: coverId,
      cover_name: cover.value.name || 'Cover Letter',
      options: {
        format: 'A4',
        margin: {
          top: '20px',
          right: '20px',
          bottom: '20px',
          left: '20px'
        }
      }
    }

    // Make request to Laravel backend
    const response = await axios.post('/api/cover-letter/generate-pdf', requestData, {
      responseType: 'blob',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/pdf'
      }
    })

    // Create and trigger download
    const blob = new Blob([response.data], { type: 'application/pdf' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `${cover.value.name || 'Cover Letter'}_${new Date().toISOString().slice(0, 10)}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)

  } catch (err) {
    console.error('PDF generation failed:', err)

    // Handle Laravel validation errors
    if (err.response?.status === 422) {
      error.value = 'Invalid data provided for PDF generation'
    } else if (err.response?.status === 500) {
      error.value = 'Server error during PDF generation'
    } else {
      error.value = 'Failed to generate PDF. Please try again.'
    }

    alert(error.value)
  } finally {
    generatingPDF.value = false
  }
}

// Get all CSS styles from the page (MISSING FUNCTION ADDED)
const getAllStyles = () => {
  let styles = ''

  // Get inline styles
  const inlineStyles = document.querySelector('style')
  if (inlineStyles) {
    styles += inlineStyles.innerHTML + '\n'
  }

  // Get external stylesheets
  const styleSheets = document.styleSheets
  for (let i = 0; i < styleSheets.length; i++) {
    try {
      const rules = styleSheets[i].cssRules || styleSheets[i].rules
      if (rules) {
        for (let j = 0; j < rules.length; j++) {
          styles += rules[j].cssText + '\n'
        }
      }
    } catch (e) {
      // Handle cross-origin stylesheets
      console.warn('Could not access stylesheet:', e)
    }
  }

  // Add Bootstrap CSS if not already included
  if (!styles.includes('bootstrap')) {
    styles += `
      /* Bootstrap essentials for PDF */
      .container-fluid { width: 100%; padding: 0; }
      .d-flex { display: flex; }
      .justify-content-center { justify-content: center; }
      .align-items-center { align-items: center; }
      .bg-white { background-color: #ffffff; }
      .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
      .p-3 { padding: 1rem; }
      .mb-4 { margin-bottom: 1.5rem; }
      .me-2 { margin-right: 0.5rem; }
    `
  }

  return styles
}

// Fetch the resume templates from the server
const fetchTemplates = () => {
  fetch('/cover-templates')
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

fetchTemplates();

// Update the selected template, update in database, and reload the page
const updateTemp = async (newTemplateId) => {
  template_id = newTemplateId

  // Update the template in the database
  try {
    loading.value = true
    await axios.post(`/api/cover/${coverId}/update-template`, {
      template_id: newTemplateId
    })
    // Optionally, you could show a success message here

    fetchCover()
  } catch (e) {
    error.value = 'Failed to update template in database.'
    loading.value = false
    console.error(e)
  }
}

// Zoom functions
const zoomIn = () => {
  zoomLevel.value = Math.min(zoomLevel.value + 0.1, 2)
}

const zoomOut = () => {
  zoomLevel.value = Math.max(zoomLevel.value - 0.1, 0.5)
}

const resetZoom = () => {
  zoomLevel.value = 1
}

// Keyboard shortcuts for zoom
const handleKeydown = (event) => {
  if (event.ctrlKey || event.metaKey) {
    switch (event.key) {
      case '=':
      case '+':
        event.preventDefault()
        zoomIn()
        break
      case '-':
        event.preventDefault()
        zoomOut()
        break
      case '0':
        event.preventDefault()
        resetZoom()
        break
    }
  }
}


onMounted(() => {
  fetchCover()
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
.pdf-controls {
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.resume-container {
  /* Ensure proper rendering for PDF */
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.5;
  color: #333;
  background: white;
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

.preview-wrapper {
  transform-origin: top center;
  transition: transform 0.2s ease;
  width: 100%;
  display: flex;
  justify-content: center;
}

.resume-container * {
  box-sizing: border-box;
}

/* Ensure text is crisp and selectable */
.resume-container p,
.resume-container h1,
.resume-container h2,
.resume-container h3,
.resume-container h4,
.resume-container h5,
.resume-container h6,
.resume-container span,
.resume-container div {
  text-rendering: optimizeLegibility;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

@media print {
  .pdf-controls {
    display: none !important;
  }

  body {
    background: white !important;
  }

  .resume-container {
    box-shadow: none !important;
    margin: 0 !important;
  }
}

.selected-temp {
  border: 3px solid rgba(14, 110, 122, 1) !important;
}

.tempImg:hover {
  border: 3px solid rgba(14, 110, 122, 1) !important;
  transition: all 0.1s ease;
}

/* Responsive Design */
@media (max-width: 991.98px) {
  .resume-container {
    max-width: 100%;
    margin: 0 auto;
    width: 100% !important;
    min-height: auto !important;
  }
  
  .pdf-controls {
    padding: 1rem !important;
  }
  
  .pdf-controls .btn {
    width: 100%;
    margin-bottom: 0.5rem;
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
}

@media (max-width: 767.98px) {
  .container-fluid {
    padding: 0.5rem !important;
  }
  
  .row.justify-content-center {
    margin: 0;
  }
  
  .col-lg-6 {
    padding: 0.5rem;
  }
  
  .tempImg {
    max-width: 100%;
    height: auto;
  }
  
  .resume-container {
    transform: scale(0.8);
    transform-origin: top center;
    margin-bottom: 2rem;
    width: 100% !important;
    min-height: auto !important;
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
}

@media (max-width: 575.98px) {
  .resume-container {
    transform: scale(0.7);
    transform-origin: top center;
    width: 100% !important;
    min-height: auto !important;
  }
  
  .tempImg {
    border-radius: 8px;
  }
  
  .selected-temp {
    border: 2px solid rgba(14, 110, 122, 1) !important;
  }
  
  .tempImg:hover {
    border: 2px solid rgba(14, 110, 122, 1) !important;
  }
}
</style>