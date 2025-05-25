import { defineStore } from "pinia";
import { reactive } from "vue";
import axios from 'axios';

export const useResumeStore = defineStore("resume", {
    state: () => ({
        resumeData: reactive({
            name: "",
            lastName: "",
            email: "",
            phone: "",
            address: "",
            city: "",
            postalCode: "",
            country: "",
            summary: "",
            employmentHistory: [],
            education: [],
            skills: [],
            orgs: [],
            lang: [],
        }),
        loading: false,
        error: null,
        savedResumeId: null,
    }),
    actions: {
        // Save resume to database
        async saveResume(data) {
            this.loading = true;
            this.error = null;

            try {
                const response = await axios.post("/api/resumes/store", data);

                if (response.data.success) {
                    this.resumeData = { ...data };
                    this.savedResumeId = response.data.data.resume_id;

                    return {
                        success: true,
                        message: response.data.message,
                        resumeId: response.data.data.resume_id,
                    };
                } else {
                    throw new Error(
                        response.data.message || "Failed to save resume"
                    );
                }
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    error.message ||
                    "An error occurred";
                console.error("Error saving resume:", error);

                return {
                    success: false,
                    message: this.error,
                    errors: error.response?.data?.errors || null,
                };
            } finally {
                this.loading = false;
            }
        },
    },
});
