<script setup>
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'

const auth = useAuthStore()
const userInitial = computed(() => auth.user?.name?.charAt(0).toUpperCase() || 'U')
const userName = computed(() => auth.user?.name || 'User')
</script>

<template>
    <nav class="navbar navbar-expand-lg bg-light py-2 border-bottom">
        <div class="container-fluid px-5">
            <router-link class="navbar-brand fw-bold" to="/home">
                <img src="https://paperink.io/img/core-img/logo.png" alt="Logo" width="50" />
            </router-link>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item mx-2">
                        <router-link class="nav-link fw-semibold" to="/resume">Resume</router-link>
                    </li>
                    <li class="nav-item mx-2">
                        <router-link class="nav-link fw-semibold" to="/cover-letter">Cover Letter</router-link>
                    </li>

                    <!-- Show Profile Circle if Authenticated -->
                    <li v-if="auth.isAuthenticated" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <span
                                class="rounded bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                style="width:35px;height:35px;font-weight:bold;">
                                {{ userInitial }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)">{{ userName }}</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                            <li><a class="dropdown-item" href="/account">My Account</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item text-danger" style="cursor:pointer"
                                    @click="auth.logout">Logout</a></li>
                        </ul>
                    </li>

                    <!-- 👇 Not Authenticated: Show login buttons -->
                    <div v-else class="d-flex align-items-center ms-3">
                        <a class="text-primary fw-semibold me-3 link theme-clr" href="javascript:void(0)"
                            data-bs-toggle="modal" data-bs-target="#loginModal">Log in</a>
                        <button class="btn theme-btn px-4 rounded-3" data-bs-toggle="modal"
                            data-bs-target="#registerModal">Get Started</button>
                    </div>
                </ul>

            </div>
        </div>
    </nav>
    <Login />
    <Register />
</template>
