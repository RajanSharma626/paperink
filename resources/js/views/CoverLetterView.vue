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
        <div class="col-6">
          <div class="row py-5">
            <div v-for="template in templates" :key="template.id" class="col-lg-4 col-12 mb-3">
              <a href="javascript:void(0)" @click="updateTemp(template.id)">
                <img :src="template.preview_img" class="img-fluid rounded custom-boc-shadow tempImg"
                  :class="{ 'selected-temp': template.id == template_id }" alt="Template Preview">
              </a>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div ref="coverContent" class="resume-container" style="width: 210mm; min-height: 297mm;">
            <component v-if="templateComponent" :is="templateComponent" :cover="cover" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
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



onMounted(fetchCover)
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
</style>