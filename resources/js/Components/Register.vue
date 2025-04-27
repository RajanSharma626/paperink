<template>
    <!-- Register Modal (Form on Left) -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="row g-0">
                    <!-- Left Side: Form -->
                    <div class="col-md-6 p-5 bg-white rounded-start position-relative">
                        <h4 class="fw-bold text-center mb-4">Sign Up</h4>
                        <form>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="New Password" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Referral Code (Optional)">
                            </div>
                            <button type="submit" class="btn theme-btn w-100 mb-3">Sign Up</button>

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
                        <button class="btn btn-light text-dark fw-semibold px-4 rounded w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Log In
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
