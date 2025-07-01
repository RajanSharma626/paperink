<template>
  <div class="print-area" v-if="templateComponent">
    <component :is="templateComponent" :cover="coverData" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const coverData = ref({})
const templateComponent = ref(null)
const route = useRoute()

onMounted(async () => {
  const res = await axios.get(`/api/cover-letters/${route.params.id}`)
  coverData.value = res.data

  const tpl = await axios.get(`/api/cover-template/${res.data.template_id}`)
  templateComponent.value = (await import(`@/Components/templates/cover-letter/${tpl.data.component}.vue`)).default
})
</script>

<style>
.print-area {
  background: white;
  width: 210mm;
  min-height: 297mm;
  padding: 20mm;
  margin: 0 auto;
  box-shadow: none;
}
</style>
