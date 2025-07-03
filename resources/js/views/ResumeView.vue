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
          <button 
            class="btn btn-primary me-2" 
            @click="generatePDF" 
            :disabled="generatingPDF"
          >
            <i class="fas fa-download me-2"></i>
            {{ generatingPDF ? 'Generating PDF...' : 'Download PDF' }}
          </button>
        </div>
      </div>

      <!-- Resume Content Container -->
      <div class="d-flex justify-content-center p-3 bg-light">
        <div ref="resumeContent" class="bg-white shadow-sm resume-container" style="width: 210mm; min-height: 297mm;">
          <component :is="templateComponent" :resume="resume" />
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
const resumeId = route.params.id

// Reactive state
const resume = ref(null)
const templateComponent = ref(null)
const loading = ref(true)
const error = ref(null)
const generatingPDF = ref(false)
const resumeContent = ref(null)

// Fetch resume data from API
const fetchResume = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await axios.get(`/api/resumes/${resumeId}`)
    
    // Handle different response formats
    resume.value = response.data.data || response.data
    
    if (!resume.value) {
      throw new Error('No resume data received')
    }

    // Dynamically load the template component
    const templateName = resume.value.template
    if (!templateName) {
      throw new Error('No template specified for this resume')
    }
    
    const module = await import(`@/components/templates/${templateName}.vue`)
    templateComponent.value = module.default

  } catch (err) {
    console.error('Error loading resume:', err)
    error.value = err.response?.data?.message || err.message || 'Failed to load resume'
  } finally {
    loading.value = false
  }
}

// Generate PDF using Laravel backend
const generatePDF = async () => {
  generatingPDF.value = true
  
  try {
    // Get the complete HTML content
    const htmlContent = resumeContent.value.outerHTML
    
    // Get all CSS styles
    const styles = getAllStyles()
    
    // Prepare data for Laravel backend
    const requestData = {
      html: htmlContent,
      styles: styles,
      resume_id: resumeId,
      resume_name: resume.value.name || 'Resume',
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
    const response = await axios.post('/api/generate-pdf', requestData, {
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
    link.download = `${resume.value.name || 'Resume'}_${new Date().toISOString().slice(0, 10)}.pdf`
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

// Get all CSS styles from the page
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

onMounted(fetchResume)
</script>

<style scoped>
.pdf-controls {
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
</style>