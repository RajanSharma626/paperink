<template>
    <div class="container py-5">
        <div class="row">
            <div class="col-6">
                <h1 class="">Cover Letters</h1>
                <div class="header-border mt-2 mb-3"></div>
                <small>Looking for create a cover letter that stand out? Try one of our cover letters templates and land
                    that job!</small>
            </div>

            <div class="col-12 justify-content-center mt-5">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <router-link class="btn theme-border w-100 theme-clr" to="/resume">Resume</router-link>
                            </div>
                            <div class="col-md-4">
                                <router-link class="btn theme-border w-100 theme-clr active" to="/cover-letter">Cover
                                    letter</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-5">
            <div v-for="template in templates" :key="template.id" class="col-lg-3 col-12 mb-3">
                <router-link :to="`/cover-editor/${template.slug}`">
                    <img :src="template.preview_img" class="img-fluid rounded custom-boc-shadow tempImg" alt="Template Preview">
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CoverLetter',
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
            fetch('/cover-templates')
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

<style scoped>
.active {
    background: linear-gradient(rgba(15, 54, 59, 1), rgba(14, 110, 122, 1)) !important;
    color: white !important;
}

.custom-boc-shadow {
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}


</style>
