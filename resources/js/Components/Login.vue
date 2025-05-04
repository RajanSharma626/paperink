<template>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="row g-0">
                    <!-- Left Side -->
                    <div
                        class="col-md-6 theme-bg text-white p-5 d-flex flex-column justify-content-center rounded-start">
                        <h3 class="fw-bold">Welcome Back!</h3>
                        <p class="mb-4">Let’s Create your account.</p>
                        <button class="btn btn-light text-dark fw-semibold px-4 rounded" data-bs-toggle="modal"
                            data-bs-target="#registerModal">Sign Up</button>
                    </div>

                    <!-- Right Side -->
                    <div class="col-md-6 p-5 bg-white rounded-end position-relative">
                        <h4 class="fw-bold text-center mb-4">Login</h4>
                        <form @submit.prevent="loginUser">
                            <div class="mb-3">
                                <input v-model="email" class="form-control" type="email" placeholder="Email" required />
                            </div>
                            <div class="mb-3">
                                <input v-model="password" class="form-control" type="password" placeholder="Password"
                                    required />
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                                </div>
                            </div>
                            <div class="mb-3 text-end">
                                <a href="#" class="small text-primary">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn theme-btn w-100 mb-3">Log In</button>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const email = ref('')
const password = ref('')
const error = ref('')
const auth = useAuthStore()

const loginUser = async () => {
  try {
    const res = await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    });

    // Login the user and store the user data and token in auth store
    auth.login(res.data.user);

    // Hide the modal
    const modalEl = document.getElementById('loginModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal?.hide();

    // Reset form values
    email.value = '';
    password.value = '';
    error.value = '';

  } catch (err) {
    // Display error if login fails
    error.value = err.response?.data?.message || 'Login failed';
  }
};


</script>
