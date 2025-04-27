<template>
    <div class="container-fluid">
        <div class="row p-5">

            <!-- Left Side: Dynamic Form -->
            <div class="col-6">
                <form>
                    <div class="row">
                        <template v-for="(field, index) in currentFormSchema" :key="index">

                            <!-- Text Input -->
                            <div v-if="field.type === 'text'" class="col-6 mb-3">
                                <label :for="field.field" class="form-label">{{ field.label }}</label>
                                <input v-model="form[field.field]" class="form-custom" />
                            </div>

                            <!-- Textarea Input -->
                            <div v-else-if="field.type === 'textarea'" class="col-12 mb-3">
                                <label :for="field.field" class="form-label">{{ field.label }}</label>
                                <textarea v-model="form[field.field]" class="form-custom"></textarea>
                            </div>

                            <!-- Dynamic Skills Array -->
                            <div v-else-if="field.type === 'skills'" class="col-12 mb-3">
                                <label>{{ field.label }}</label>
                                <div class="row">
                                    <div v-for="(skill, skillIndex) in form.skills" :key="skillIndex"
                                        class="col-6 mb-3 d-flex align-items-center">
                                        <input v-model="skill.skill" class="form-custom" />
                                        <button @click.prevent="removeSkill(skillIndex)" class="btn btn-danger ms-2">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <button @click.prevent="addSkill" class="btn btn-primary mt-2">
                                            Add Skill
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </template>
                    </div>
                </form>
            </div>

            <!-- Right Side: Live Preview -->
            <div class="col-6 p-6 overflow-auto bg-gray-50">
                <ResumeTemplate :resume="form" />
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import ResumeTemplate from '@/components/templates/ResumeTemplate1.vue'

const form = ref({
    name: '',
    lastName: '',
    jobTitle: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    country: '',
    postalCode: '',
    summary: '',
    skills: [],
})

// Add more form schema fields as required
const formSchemas = {
    ResumeTemplate1: [
        { field: 'name', label: 'First Name', type: 'text' },
        { field: 'lastName', label: 'Last Name', type: 'text' },
        { field: 'jobTitle', label: 'Job Title', type: 'text' },
        { field: 'email', label: 'Email', type: 'text' },
        { field: 'phone', label: 'Phone', type: 'text' },
        { field: 'address', label: 'Address', type: 'text' },
        { field: 'city', label: 'City/State', type: 'text' },
        { field: 'country', label: 'Country', type: 'text' },
        { field: 'postalCode', label: 'Postal Code', type: 'text' },
        { field: 'summary', label: 'Summary', type: 'textarea' },
        { field: 'skills', label: 'Skills', type: 'skills' },
    ],
}

const currentFormSchema = computed(() => formSchemas.ResumeTemplate1)

// Functions for dynamic skills array
function addSkill() {
    form.value.skills.push({ skill: '' })
}

function removeSkill(index) {
    form.value.skills.splice(index, 1)
}
</script>

<style scoped>
label {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 14px;
    line-height: 20px;
    color: rgb(130, 139, 162);
    font-weight: 300;
}

.form-custom {
    border-radius: 3px;
    padding: 12px 16px;
    width: 100%;
    display: block;
    caret-color: #0e6e7a;
    background-color: rgb(239, 242, 249);
    outline: none;
    border: 0px;
    color: rgb(30, 37, 50);
    transition: color 0.1s;
}

.form-custom:focus {
    /* box-shadow: 0px 0px 0px 2px rgb(26, 145, 240); */
    /* background-color: rgb(255, 255, 255); */
    color: rgb(30, 37, 50);
    border-bottom: 2px solid #0e6e7a;
}
</style>