import { defineStore } from "pinia";
import { reactive } from "vue";
import axios from "axios";
import { useAuthStore } from "./auth"; // Import the auth store

export const useResumeStore = defineStore("resume", {
    state: () => ({
        resumeData: reactive({
            user_id: null, // Add user_id to resumeData
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
        async saveResume(data, template_id = null) {
            this.loading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                // Add user_id and template_id to resume data
                const resumeDataWithUser = {
                    ...data,
                    user_id: authStore.user?.id || null,
                    template_id: template_id,
                };

                const response = await axios.post(
                    "/api/resumes/store",
                    resumeDataWithUser
                );

                if (response.data.success) {
                    this.resumeData = {
                        ...data,
                        user_id: resumeDataWithUser.user_id,
                        template_id,
                    }; // Set user_id and template_id in resumeData
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
