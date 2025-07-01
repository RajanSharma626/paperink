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
});
