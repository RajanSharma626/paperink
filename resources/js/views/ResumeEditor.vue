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
import QuillEditor from '@/components/form/QuillEditor.vue'
import html2pdf from 'html2pdf.js'
import { jsPDF } from 'jspdf';
import 'jspdf-autotable';

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
      resumeStore.saveResume(resumeStore.resumeData)
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
  // Create a new PDF document
  const doc = new jsPDF({
    orientation: 'portrait',
    unit: 'mm',
    format: 'a4',
    compress: false
  });

  // Set document properties for better indexing and searchability
  doc.setProperties({
    title: `${resumeStore.resumeData.name} ${resumeStore.resumeData.lastName} - Resume`,
    subject: 'Professional Resume',
    author: resumeStore.resumeData.name + ' ' + resumeStore.resumeData.lastName,
    keywords: 'resume, cv, ' + resumeStore.resumeData.jobTitle,
    creator: 'Resume Builder'
  });

  // Set default font
  doc.setFont('helvetica', 'normal');
  
  // Page dimensions
  const pageWidth = doc.internal.pageSize.width;
  const margin = 15;
  const usableWidth = pageWidth - (margin * 2);
  
  let yPosition = margin;
  
  // Helper function to add text with proper wrapping
  function addText(text, x, y, options = {}) {
    const defaultOptions = {
      fontSize: 10,
      fontStyle: 'normal',
      maxWidth: usableWidth,
      lineHeightFactor: 1.2
    };
    const mergedOptions = {...defaultOptions, ...options};
    
    doc.setFontSize(mergedOptions.fontSize);
    doc.setFont('helvetica', mergedOptions.fontStyle);
    
    // Handle HTML content by stripping tags
    const cleanText = text ? text.replace(/<[^>]*>/g, '') : '';
    
    const textLines = doc.splitTextToSize(cleanText, mergedOptions.maxWidth);
    doc.text(textLines, x, y);
    
    return y + (textLines.length * mergedOptions.fontSize * 0.352778 * mergedOptions.lineHeightFactor);
  }
  
  // Header section
  yPosition = addText(`${resumeStore.resumeData.name} ${resumeStore.resumeData.lastName}`, margin, yPosition + 10, {
    fontSize: 20,
    fontStyle: 'bold'
  });
  
  yPosition = addText(resumeStore.resumeData.jobTitle, margin, yPosition + 5, {
    fontSize: 14,
    fontStyle: 'italic'
  });
  
  // Contact information
  const contactInfo = [
    resumeStore.resumeData.email,
    resumeStore.resumeData.phone,
    `${resumeStore.resumeData.address}, ${resumeStore.resumeData.city}, ${resumeStore.resumeData.postalCode}`,
    resumeStore.resumeData.country
  ].filter(Boolean).join(' | ');
  
  yPosition = addText(contactInfo, margin, yPosition + 6, {
    fontSize: 9
  });
  
  // Divider line
  yPosition += 5;
  doc.setLineWidth(0.5);
  doc.line(margin, yPosition, pageWidth - margin, yPosition);
  yPosition += 5;
  
  // Summary section
  if (resumeStore.resumeData.summary) {
    yPosition = addText('PROFESSIONAL SUMMARY', margin, yPosition + 5, {
      fontSize: 12,
      fontStyle: 'bold'
    });
    
    yPosition = addText(resumeStore.resumeData.summary, margin, yPosition + 4);
    yPosition += 5;
  }
  
  // Experience section
  if (resumeStore.resumeData.employmentHistory && resumeStore.resumeData.employmentHistory.length > 0) {
    yPosition = addText('WORK EXPERIENCE', margin, yPosition + 5, {
      fontSize: 12,
      fontStyle: 'bold'
    });
    
    resumeStore.resumeData.employmentHistory.forEach(job => {
      // Check for page overflow
      if (yPosition > 270) {
        doc.addPage();
        yPosition = margin;
      }
      
      const dateRange = [
        job.startDate ? new Date(job.startDate).toLocaleDateString('en-US', {year: 'numeric', month: 'short'}) : '',
        job.endDate ? new Date(job.endDate).toLocaleDateString('en-US', {year: 'numeric', month: 'short'}) : 'Present'
      ].filter(Boolean).join(' - ');
      
      yPosition = addText(`${job.jobTitle} at ${job.company}`, margin, yPosition + 5, {
        fontSize: 11,
        fontStyle: 'bold'
      });
      
      yPosition = addText(`${dateRange} | ${job.city}`, margin, yPosition + 4, {
        fontSize: 9,
        fontStyle: 'italic'
      });
      
      yPosition = addText(job.description, margin, yPosition + 4);
      yPosition += 3;
    });
  }
  
  // Education section
  if (resumeStore.resumeData.education && resumeStore.resumeData.education.length > 0) {
    // Check for page overflow
    if (yPosition > 270) {
      doc.addPage();
      yPosition = margin;
    }
    
    yPosition = addText('EDUCATION', margin, yPosition + 5, {
      fontSize: 12,
      fontStyle: 'bold'
    });
    
    resumeStore.resumeData.education.forEach(edu => {
      const dateRange = [
        edu.startDate ? new Date(edu.startDate).toLocaleDateString('en-US', {year: 'numeric', month: 'short'}) : '',
        edu.endDate ? new Date(edu.endDate).toLocaleDateString('en-US', {year: 'numeric', month: 'short'}) : 'Present'
      ].filter(Boolean).join(' - ');
      
      yPosition = addText(`${edu.degree}`, margin, yPosition + 5, {
        fontSize: 11,
        fontStyle: 'bold'
      });
      
      yPosition = addText(`${edu.school} | ${dateRange} | ${edu.city}`, margin, yPosition + 4, {
        fontSize: 9,
        fontStyle: 'italic'
      });
      
      yPosition = addText(edu.description, margin, yPosition + 4);
      yPosition += 3;
    });
  }
  
  // Skills section
  if (resumeStore.resumeData.skills && resumeStore.resumeData.skills.length > 0) {
    // Check for page overflow
    if (yPosition > 270) {
      doc.addPage();
      yPosition = margin;
    }
    
    yPosition = addText('SKILLS', margin, yPosition + 5, {
      fontSize: 12,
      fontStyle: 'bold'
    });
    
    // Organize skills by level
    const skillsByLevel = {
      'Expert': [],
      'Intermediate': [],
      'Beginner': []
    };
    
    resumeStore.resumeData.skills.forEach(skill => {
      if (skill.level && skillsByLevel[skill.level]) {
        skillsByLevel[skill.level].push(skill.skill);
      }
    });
    
    // Display skills by level
    Object.entries(skillsByLevel).forEach(([level, skills]) => {
      if (skills.length > 0) {
        yPosition = addText(`${level}:`, margin, yPosition + 4, {
          fontSize: 10,
          fontStyle: 'bold'
        });
        
        yPosition = addText(skills.join(', '), margin + 5, yPosition + 4);
      }
    });
  }
  
  // Save the PDF
  const fileName = `${resumeStore.resumeData.name}_${resumeStore.resumeData.lastName}_Resume.pdf`;
  doc.save(fileName);
  
  console.log('PDF successfully generated');
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
