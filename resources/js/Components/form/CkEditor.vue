<template>
  <ckeditor
    :editor="Editor"
    v-model="model"
    :config="editorConfig"
  />
</template>

<script setup>
import { ref, watch } from 'vue'
import { CKEditor } from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

const props = defineProps({
  modelValue: { type: String, default: '' }
})

const emit = defineEmits(['update:modelValue'])

const model = ref(props.modelValue)

watch(model, (newVal) => {
  emit('update:modelValue', newVal)
})

watch(() => props.modelValue, (newVal) => {
  model.value = newVal
})

const Editor = ClassicEditor

const editorConfig = {
  toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo', 'link'],
  placeholder: 'Start typing your professional summary...'
}
</script>
