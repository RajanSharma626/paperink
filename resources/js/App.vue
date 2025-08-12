<template>
    <div>
        <Navbar
            v-if="!route.path.includes('resume-editor') && !route.path.includes('cover-editor') && !route.path.match(/^\/resume\/\d+\/edit$/)" />
        <router-view />
        <Footer
            v-if="!route.path.includes('resume-editor') && !route.path.includes('cover-editor') && !route.path.match(/^\/resume\/\d+\/edit$/)" />
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
</script>
