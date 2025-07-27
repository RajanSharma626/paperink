import { defineStore } from "pinia";
import { reactive } from "vue";

export const useCoverStore = defineStore("cover", {
    state: () => ({
        coverData: reactive({
            name: "",
            email: "",
            phone: "",
            address: "",
            jobTitle: "",
            company: "",
            hiringManager: "",
            letter: "",
            user_id: null,
            template_id: null,
        }),
    }),
    actions: {
        async saveCoverLetter(data, template_id = null) {
            try {
                const { useAuthStore } = await import("./auth");
                const authStore = useAuthStore();
                const coverDataWithUser = {
                    ...data,
                    user_id: authStore.user?.id || null,
                    template_id: template_id,
                };
                const response = await (await import('axios')).default.post(
                    "/api/cover-letters",
                    coverDataWithUser
                );
                if (response.data.success) {
                    this.coverData = {
                        ...data,
                        user_id: coverDataWithUser.user_id,
                        template_id,
                    };
                    return {
                        success: true,
                        message: response.data.message,
                        coverLetterId: response.data.data?.cover_letter_id,
                    };
                } else {
                    throw new Error(response.data.message || "Failed to save cover letter");
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || error.message || "An error occurred";
                console.error("Error saving cover letter:", error);
                return {
                    success: false,
                    message: errorMsg,
                    errors: error.response?.data?.errors || null,
                };
            }
        }
    },
});
