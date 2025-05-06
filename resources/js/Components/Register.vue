<template>
    <!-- Register Modal (Form on Left) -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true" ref="modalEl">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="row g-0">
                    <!-- Left Side: Form -->
                    <div class="col-md-6 p-5 bg-white rounded-start position-relative">
                        <h4 class="fw-bold text-center mb-4">Sign Up</h4>
                        <form @submit.prevent="registerUser">
                            <div class="mb-3">
                                <input v-model="username" type="text" class="form-control" placeholder="Username"
                                    required>
                            </div>
                            <div class="mb-3">
                                <input v-model="email" type="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input v-model="password" type="password" class="form-control"
                                    placeholder="New Password" required>
                                <small v-if="passwordError" class="text-danger">{{ passwordError }}</small>
                            </div>
                            <div class="mb-3">
                                <input v-model="referralCode" type="text" class="form-control"
                                    placeholder="Referral Code (Optional)">
                            </div>
                            <button type="submit" class="btn theme-btn w-100 mb-3" :disabled="isLoading">
                                <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status"
                                    aria-hidden="true"></span>
                                {{ isLoading ? 'Creating Account...' : 'Sign Up' }}
                            </button>

                            <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>

                            <div class="text-center my-3">OR</div>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#"><img src="https://img.icons8.com/color/48/000000/google-logo.png"
                                        width="32" /></a>
                                <a href="#"><img src="https://img.icons8.com/color/48/000000/linkedin.png"
                                        width="32" /></a>
                            </div>
                        </form>
                    </div>

                    <!-- Right Side: Welcome message -->
                    <div
                        class="col-md-6 theme-bg text-white p-5 d-flex flex-column justify-content-center align-items-center rounded-end">
                        <h3 class="fw-bold">Welcome to Paperink.io</h3>
                        <p class="mb-4">Already a member? Log in to continue building your future.</p>
                        <button class="btn btn-light text-dark fw-semibold px-4 rounded w-100" data-bs-toggle="modal"
                            data-bs-target="#loginModal" @click="switchToLogin">
                            Log In
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'

const username = ref('')
const email = ref('')
const password = ref('')
const referralCode = ref('')
const error = ref('')
const passwordError = ref('')
const isLoading = ref(false)
const auth = useAuthStore()
const modalEl = ref(null)
let bootstrapModal = null

// Initialize the Bootstrap modal after component is mounted
onMounted(() => {
    if (modalEl.value) {
        bootstrapModal = new bootstrap.Modal(modalEl.value)
    }
})

// Validate password
const validatePassword = () => {
    if (password.value.length < 8) {
        passwordError.value = 'Password must be at least 8 characters'
        return false
    }

    // Check for at least one uppercase letter, one lowercase letter, and one number
    const hasUppercase = /[A-Z]/.test(password.value)
    const hasLowercase = /[a-z]/.test(password.value)
    const hasNumber = /[0-9]/.test(password.value)

    if (!hasUppercase || !hasLowercase || !hasNumber) {
        passwordError.value = 'Password must contain at least one uppercase letter, one lowercase letter, and one number'
        return false
    }

    passwordError.value = ''
    return true
}

const registerUser = async () => {
    // Reset errors
    error.value = ''

    // Validate password
    if (!validatePassword()) {
        return
    }

    isLoading.value = true

    try {
        const res = await axios.post('/api/register', {
            name: username.value,
            email: email.value,
            password: password.value,
            referralCode: referralCode.value || null
        })

        // If registration is successful, login the user
        auth.login(res.data.user)

        // Hide the modal
        if (bootstrapModal) {
            bootstrapModal.hide()
        } else {
            // Fallback method if modal instance isn't available
            const modalElement = document.getElementById('registerModal')
            if (modalElement) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement)
                modalInstance?.hide()
            }
        }

        // Reset form values
        username.value = ''
        email.value = ''
        password.value = ''
        referralCode.value = ''

    } catch (err) {
        console.error(err)
        // Display error if registration fails
        error.value = err.response?.data?.message || 'Registration failed. Please try again.'
    } finally {
        isLoading.value = false
    }
}

// Switch to login modal
const switchToLogin = () => {
    // Hide register modal first
    if (bootstrapModal) {
        bootstrapModal.hide()
    } else {
        const modalElement = document.getElementById('registerModal')
        if (modalElement) {
            const modalInstance = bootstrap.Modal.getInstance(modalElement)
            modalInstance?.hide()
        }
    }

    // Small delay to allow animations to complete
    setTimeout(() => {
        // Then show login modal
        const loginModal = document.getElementById('loginModal')
        if (loginModal) {
            const loginModalInstance = new bootstrap.Modal(loginModal)
            loginModalInstance.show()
        }
    }, 350)
}
</script>

<style scoped>
.theme-bg {
    background-color: #4361ee;
}

.theme-btn {
    background-color: #4361ee;
    color: white;
}

.theme-btn:hover {
    background-color: #3651cc;
    color: white;
}
</style>
