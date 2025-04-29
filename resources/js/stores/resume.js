
import { defineStore } from 'pinia'
import { reactive } from 'vue'

export const useResumeStore = defineStore('resume', {
  state: () => ({
    resumeData: reactive({
      name: '',
      lastName: '',
      email: '',
      phone: '',
      address: '',
      city: '',
      postalCode: '',
      country: '',
      summary: '',
      employmentHistory: [],
      education: [],
      skills: [],
      orgs: [],
      lang: [],
    })
  }),
})
