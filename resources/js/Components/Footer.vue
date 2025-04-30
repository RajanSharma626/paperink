<template>
    <footer class="bg-light text-dark pt-5 pb-3 border-top">
        <div class="container">
            <div class="row">
                <!-- Logo and Social -->
                <div class="col-md-3 mb-4">
                    <img src="https://paperink.io/img/core-img/logo.png" alt="Logo" height="60" class="mb-3" />
                    <div class="d-flex gap-2">
                        <a href="https://instagram.com" target="_blank"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="https://linkedin.com" target="_blank"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>

                <!-- Our Services -->
                <!-- <div class="col-md-3 mb-4">
                    <h6 class="fw-bold">Our Services</h6>
                    <ul class="list-unstyled">
                        <li><router-link to="/resume" class="text-dark text-decoration-none">Resume</router-link></li>
                        <li><router-link to="/cover-letter" class="text-dark text-decoration-none">Cover
                                Letter</router-link></li>
                        <li><router-link to="/portfolio" class="text-dark text-decoration-none">Portfolio</router-link>
                        </li>
                        <li><router-link to="/affiliate" class="text-dark text-decoration-none">Affiliate</router-link>
                        </li>
                    </ul>
                </div> -->

                <!-- Navigate -->
                <!-- <div class="col-md-3 mb-4">
                    <h6 class="fw-bold">Navigate</h6>
                    <ul class="list-unstyled">
                        <li><router-link to="/" class="text-dark text-decoration-none">Home</router-link></li>
                        <li><router-link to="/about" class="text-dark text-decoration-none">About us</router-link></li>
                        <li><router-link to="/pricing" class="text-dark text-decoration-none">Pricing</router-link></li>
                        <li><router-link to="/faq" class="text-dark text-decoration-none">FAQ</router-link></li>
                    </ul>
                </div> -->

                <!-- Support -->
                <!-- <div class="col-md-3 mb-4">
                    <h6 class="fw-bold">Support</h6>
                    <ul class="list-unstyled">
                        <li><router-link to="/privacy-policy" class="text-dark text-decoration-none">Privacy
                                Policy</router-link></li>
                        <li><router-link to="/contact" class="text-dark text-decoration-none">Contact Us</router-link>
                        </li>
                        <li><router-link to="/career-coaching" class="text-dark text-decoration-none">Career
                                Coaching</router-link></li>
                        <li><router-link to="/blogs" class="text-dark text-decoration-none">Blogs</router-link></li>
                    </ul>
                </div> -->
            </div>

            <div class="text-center mt-4 small text-secondary">
                Copyright © 2024 All rights reserved PaperInk.io
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'Footer',
};
</script>

<style scoped>
footer a:hover {
    color: #0d6efd;
}
</style>
