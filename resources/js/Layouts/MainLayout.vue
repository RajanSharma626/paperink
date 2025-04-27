<template>
    <div>
        <Navbar />
        <Login />
        <Register />
        <main class="container mx-auto p-4">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Navbar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import Login from '../Components/Login.vue'
import Register from '../Components/Register.vue'
</script>
