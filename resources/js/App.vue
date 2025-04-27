<template>
    <div>
        <Navbar />
        <router-view /> <!-- this will load your views dynamically -->
        <Footer />
    </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
</script>
