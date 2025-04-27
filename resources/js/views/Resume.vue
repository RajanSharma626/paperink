<script>
export default {
    name: 'Resume',
    data() {
        return {
            templates: [],
        };
    },
    mounted() {
        this.fetchTemplates();
    },
    methods: {
        fetchTemplates() {
            fetch('/resume-templates')
                .then(response => {
                    return response.text();
                })
                .then(text => {

                    // Try to continue if it looks like JSON
                    try {
                        const data = JSON.parse(text);
                        this.templates = data;
                    } catch (e) {
                        console.error('Not valid JSON:', e);
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                });
        }
    }
}

</script>

<template>
    <div class="container py-5">
        <div class="row">
            <div class="col-6">
                <h1 class="">My Resumes</h1>
                <div class="header-border mt-2 mb-3"></div>
                <small>Pick one of our free resume template, fill it out, and land that dream job! Create and
                    Download your
                    professional resume in less than 5 minutes.</small>
            </div>

            <div class="col-12 justify-content-center mt-5">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <router-link class="btn theme-border w-100 theme-clr active"
                                    to="/resume">Resume</router-link>
                            </div>
                            <div class="col-md-4">
                                <router-link class="btn theme-border w-100 theme-clr" to="/cover-letter">Cover
                                    letter</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-5">
            <div v-for="template in templates" :key="template.id" class="col-lg-3 col-12 mb-3">
                <router-link :to="`/resume-editor/${template.slug}`">
                    <img :src="template.preview_img" class="img-fluid rounded custom-boc-shadow" alt="Template Preview">

                    <a :href="`/preview-template/${template.id}`" class="btn my-btn-bg fs-6 preview-btn">
                        <i class="bi bi-eye"></i>
                    </a>
                </router-link>
            </div>
        </div>

    </div>
</template>


<style scoped>
.active {
    background: linear-gradient(rgba(15, 54, 59, 1), rgba(14, 110, 122, 1)) !important;
    color: white !important;
}

.custom-boc-shadow {
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
</style>
