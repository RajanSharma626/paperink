<!-- QuillEditor.vue -->
<script>
// Import Vue 3 composition API
import { computed, onMounted, ref } from 'vue'

// Import Quill directly instead of using the Vue wrapper
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

export default {
    name: 'QuillEditor',
    props: {
        modelValue: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: 'write here...'
        },
        readonly: {
            type: Boolean,
            default: false
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const editorRef = ref(null)
        const quill = ref(null)

        // Initialize Quill on mount
        onMounted(() => {
            if (editorRef.value) {
                // Basic Quill toolbar configuration
                const toolbarOptions = [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }]
                ]

                // Create Quill instance
                quill.value = new Quill(editorRef.value, {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    placeholder: props.placeholder,
                    readOnly: props.readonly,
                    theme: 'snow'
                })

                // Set initial content
                if (props.modelValue) {
                    quill.value.root.innerHTML = props.modelValue
                }

                // Handle content changes
                quill.value.on('text-change', () => {
                    emit('update:modelValue', quill.value.root.innerHTML)
                })
            }
        })

        // Watch for external changes to modelValue
        const content = computed({
            get: () => props.modelValue,
            set: (value) => {
                emit('update:modelValue', value)
                // Update the editor content if it exists and differs from current value
                if (quill.value && quill.value.root.innerHTML !== value) {
                    quill.value.root.innerHTML = value
                }
            }
        })

        return { editorRef, content }
    }
}
</script>

<template>
    <div class="quill-editor-container">
        <div ref="editorRef" class="quill-editor"></div>
    </div>
</template>

<style scoped>
.quill-editor-container {
    width: 100%;
    caret-color: #0e6e7a;
    background: rgb(239, 242, 249) !important;
}

.quill-editor {
    height: 150px;
    overflow-y: auto;
}

</style>