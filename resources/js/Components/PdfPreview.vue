<template>
    <div class="pdf-preview-container">
        <!-- Loading state (will be hidden by CSS for PDF) -->
        <div v-if="!templateComponent" class="loading d-flex justify-content-center align-items-center"
            style="height: 100vh;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading template...</span>
            </div>
        </div>

        <!-- Your actual Vue component -->
        <div v-else class="preview-content">
            <component :is="templateComponent" :cover="coverData" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'

const props = defineProps({
    templateId: {
        type: String,
        required: true
    },
    coverData: {
        type: Object,
        required: true
    }
})

const templateComponent = ref(null)

const loadTemplate = async () => {
    try {
        console.log('Loading template:', props.templateId)

        // Get template info from your API
        const { data } = await axios.get(`/api/cover-template/${props.templateId}`)
        const componentName = data.component

        console.log('Loading component:', componentName)

        // Dynamically import your existing Vue component
        const module = await import(`@/Components/templates/cover-letter/${componentName}.vue`)
        templateComponent.value = module.default

        // Wait for next tick to ensure component is mounted
        await nextTick()

        console.log('Template loaded successfully')

        // Signal that component is ready
        window.dispatchEvent(new Event('template-loaded'))

    } catch (error) {
        console.error('Failed to load template:', error)

        // Fallback - show error state
        templateComponent.value = {
            template: '<div class="alert alert-danger">Failed to load template</div>'
        }
    }
}

onMounted(async () => {
    await loadTemplate()
})
</script>

<style scoped>
.pdf-preview-container {
    width: 100%;
    height: 100vh;
    margin: 0;
    padding: 0;
    overflow: visible;
}

.preview-content {
    width: 100%;
    height: 100%;
}

.loading {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    z-index: 9999;
}

/* Ensure the component renders at full size for PDF */
.preview-content :deep(.page) {
    margin: 0 !important;
    box-shadow: none !important;
}

.preview-content :deep(.preview-scale) {
    transform: scale(1) !important;
}
</style>