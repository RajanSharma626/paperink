<template>
    <div>
        <Navbar />
        <main class="container mx-auto p-4">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Navbar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
</script>
