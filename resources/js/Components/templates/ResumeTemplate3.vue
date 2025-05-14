<template>
    <div class="page" id="page">
        <div class="main">
            <div class="top">
                <div v-if="resume.name">
                    <div class="name">{{ resume.name + ' ' + resume.lastName }}</div>
                </div>
                <div v-if="resume.jobTitle">
                    <div class="profession">{{ resume.jobTitle }}</div>
                </div>
                <div v-if="resume.summary" >
                    <div class="bio" v-html="resume.summary"></div>
                </div>
            </div>

            <div class="middle" v-if="hasContactDetails">
                <div class="details-section">
                    <div v-if="resume.email" class="details">
                        <i class="icons bi-envelope-fill"></i>{{ resume.email }}
                    </div>
                    <div v-if="resume.phone" class="details">
                        <i class="icons bi-phone-fill"></i>{{ resume.phone }}
                    </div>
                    <div v-if="resume.address" class="details">
                        <i class="icons bi-geo-alt-fill"></i>{{ [resume.address, resume.city, resume.postalCode,
            resume.country].filter(Boolean).join(', ') }}
                    </div>
                </div>
            </div>

            <div class="end">
                <div v-if="resume.skills && resume.skills.length" class="skills">
                    <h4>Skills</h4>
                    <div class="skills-section">
                        <div v-for="(skill, index) in resume.skills" :key="index" class="skill-section">
                           {{ skill.skill }}
                        </div>
                    </div>
                </div>

                <div v-if="resume.employmentHistory?.length" class="work-experience">
                    <h4>Work experience</h4>
                    <div v-for="(exp, i) in resume.employmentHistory" :key="i" class="work-experience-para">
                        <div class="line-section">
                            <div class="line"></div>
                            <h5>{{ exp.jobTitle }}</h5>
                        </div>
                        <h6>{{ exp.company }}</h6>
                        <span class="date">
                            <span>{{ exp.startDate }} - {{ exp.endDate }}</span>
                            <span>{{ exp.city }}</span>
                        </span>
                        <div v-html="exp.description"></div>
                    </div>
                </div>

                <div v-if="resume.education?.length" class="education">
                    <h4>Education</h4>
                    <div v-for="(edu, i) in resume.education" :key="i" class="education-para">
                        <div class="line-section">
                            <div class="line"></div>
                            <h5>{{ edu.degree }}</h5>
                        </div>
                        <h6>{{ edu.school }}</h6>
                        <span class="date">
                            <span>{{ edu.startDate }} - {{ edu.endDate }}</span>
                            <span>{{ edu.city }}</span>
                        </span>
                        <div v-html="edu.description"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps({
    resume: { type: Object, required: true }
})

const hasContactDetails = computed(() => {
    return props.resume.email || props.resume.phone || props.resume.address
})

const leftOrgs = computed(() => props.resume.orgs?.filter((_, i) => i % 2 === 0) || [])
const rightOrgs = computed(() => props.resume.orgs?.filter((_, i) => i % 2 !== 0) || [])
</script>

<style scoped>
.page {
    background: #fff;
    width: 210mm;
    min-height: 297mm;
    margin: 10mm auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.main {
    padding-top: 2mm;
    padding-bottom: 6mm;
}

.top {
    padding: 5mm 10mm;
    line-height: 1.2;
}

.top .name {
    font-size: 30px;
}

.top .profession {
    font-size: 18px;
    color: #313c4e;
}

.top .bio {
    font-size: 14px !important;
    line-height: 1.4;
    padding: 1mm 0;
}

.middle {
    padding: 1mm 10mm;
    background-color: #313c4e;
    color: #fff;
}

.middle .details-section {
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 2mm 0;
}

.details-section .details {
    font-size: 12px !important;
    font-weight: 400;
}

.details .icons {
    padding-right: 8px;
    font-size: 12px !important;
    color: #fff;
}

.end {
    padding: 2mm 0mm;
}

.skills {
    line-height: 1.2;
    padding: 1mm 10mm;
}

.skills h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #313c4e;
    margin: 5px 0;
}

.skills-section {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.skill-section {
    width: 25%;
    line-height: 1.8;
}

.work-experience {
    line-height: 1.2;
}

.work-experience h4 {
    padding: 0 10mm;
    font-size: 18px;
    text-transform: uppercase;
    color: #313c4e;
    margin: 5px 0;
}

.line-section {
    display: flex;
    align-items: center;
    padding-right: 10mm;
    padding-top: 3mm;
}

.line {
    width: 5mm;
    height: 2px;
    background-color: #000;
    margin-right: 10px;
}

.work-experience-para {
    padding: 0 10mm;
}

.work-experience-para h6 {
    font-size: 16px;
    font-weight: 500;
    padding: 1mm 0;
}

.work-experience-para .date {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    font-style: italic;
    color: #313c4e;
    padding: 4px 0;
}

.work-experience-para p {
    font-size: 14px !important;
    line-height: 1.4;
}

.work-experience-para li::marker {
    color: #313c4e;
}

.education {
    line-height: 1.2;
}

.education h4 {
    padding: 0 10mm;
    font-size: 18px;
    text-transform: uppercase;
    color: #313c4e;
    margin: 6px 0;
}

.education-para {
    padding: 0 10mm;
}

.education-para h6 {
    font-size: 16px;
    font-weight: 500;
    padding: 1mm 0;
}

.education-para .date {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    font-style: italic;
    color: #313c4e;
    padding: 4px 0;
}

.organization {
    line-height: 1.2;
    padding: 0 10mm;
}

.organization h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #313c4e;
    margin: 5px 0;
}

.organization-section {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
}

.organization-section .left p {
    padding-bottom: 4mm;
}

.languages {
    line-height: 1.2;
    padding: 0 10mm;
}

.languages h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #313c4e;
}

.languages-section {
    display: flex;
    gap: 35px;
    padding-top: 2mm;
}

.languages-section p {
    font-size: 14px;
}

.languages-section p span {
    font-size: 12px;
    font-style: italic;
    color: #313c4e;
    padding: 3px 0;
}

@media print {

    body,
    .page {
        margin: 0;
        box-shadow: none;
    }
}
</style>
