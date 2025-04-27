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
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-2">
                        <router-link class="nav-link fw-semibold" to="/resume">Resume</router-link>
                    </li>
                    <li class="nav-item mx-2">
                        <router-link class="nav-link fw-semibold" to="/cover-letter">Cover Letter</router-link>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link fw-semibold" href="#">Services</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link fw-semibold" href="#">Pricing</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center ms-3">
                    <a class="text-primary fw-semibold me-3 link theme-clr" href="#" data-bs-toggle="modal"
                        data-bs-target="#loginModal">Log in</a>
                    <button class="btn theme-btn px-4 rounded-3" data-bs-toggle="modal"
                        data-bs-target="#registerModal">Get Started</button>
                </div>
            </div>
        </div>
    </nav>
</template>
