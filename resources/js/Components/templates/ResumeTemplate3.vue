<template>
    <div class="page" id="page">
        <div class="main">
            <div class="top">
                <div v-if="resume.name">
                    <div v-if="resume.name" class="name fw-semibold">{{ resume.name }} <span v-if="resume.lastName"> {{ resume.lastName }}</span></div>
                </div>
                <div v-if="resume.jobTitle">
                    <div class="profession">{{ resume.jobTitle }}</div>
                </div>
                <div v-if="resume.summary">
                    <div class="bio" v-html="resume.summary"></div>
                </div>
            </div>
            <div class="middle" v-if="hasContactDetails">
                <div class="details-section">
                    <p v-if="resume.email" class="details mb-0"><i class="icons bi-envelope-fill"></i>{{ resume.email }}</p>
                    <p v-if="resume.phone" class="details mb-0"><i class="icons bi-phone-fill"></i>{{ resume.phone }}</p>
                    <p v-if="resume.address" class="details mb-0">
                        <i class="icons bi-geo-alt-fill"></i>
                        {{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}
                    </p>
                    <!-- <p class="details"><i class="icons bi-linkedin"></i>linkedin.com/in/jon.snow</p>
                    <p class="details"><i class="icons bi bi-skype"></i>jon.snow</p> -->
                </div>
            </div>
            <div class="end">
                <div v-if="resume.skills && resume.skills.length" class="skills">
                    <h4 class="fw-bold">Skills</h4>
                    <div class="skills-section">
                        <div v-for="(skill, index) in resume.skills" :key="index" class="skill-section">
                            <p class="mb-0 fs-16">
                                {{ skill.skill || skill.skill_name }}
                            </p>
                        </div>
                    </div>
                </div>
                <br v-if="resume.employmentHistory && resume.employmentHistory.length">
                <div v-if="resume.employmentHistory && resume.employmentHistory.length" class="work-experience">
                    <h4 class="mb-0 fw-bold">Work experience</h4>
                    <div v-for="(exp, i) in resume.employmentHistory" :key="i">
                        <div class="line-section">
                            <div class="line"></div>
                            <h6 class="mb-0 ms-2 fw-semibold">{{ exp.jobTitle }}</h6>
                        </div>
                        <div class="work-experience-para">
                            <h6 class="text-muted mb-0">{{ exp.company }}</h6>
                            <span class="date">
                                <span>{{ exp.startDate || exp.startYear }} - {{ exp.endDate || exp.endYear || "Present"  }}</span>
                                <span>{{ exp.city || exp.address }}</span>
                            </span>
                            <p v-if="exp.description" v-html="exp.description"></p>
                        </div>
                    </div>
                </div>
                <br v-if="(resume.employmentHistory && resume.employmentHistory.length) || (resume.education && resume.education.length)">
                <div v-if="resume.education && resume.education.length" class="education">
                    <h4 class="mb-0 fw-bold">Education</h4>
                    <div v-for="(edu, i) in resume.education" :key="i">
                        <div class="line-section">
                            <div class="line"></div>
                            <h6 class="mb-0 fw-semibold ms-2">{{ edu.degree }}</h6>
                        </div>
                        <div class="education-para">
                            <h6 class="text-muted mb-0">{{ edu.university || edu.school }}</h6>
                            <span class="date">{{ edu.startYear || edu.startDate }} - {{ edu.endYear || edu.endDate || "Present"  }}</span>
                            <div v-if="edu.description" v-html="edu.description"></div>
                        </div>
                    </div>
                </div>
                <br v-if="resume.orgs && resume.orgs.length">
                <div v-if="resume.orgs && resume.orgs.length" class="organization">
                    <h4>Organizations</h4>
                    <div class="organization-section">
                        <div class="left">
                            <p v-for="(org, index) in resume.orgs" v-if="index % 2 === 0" :key="'left-' + index">
                                {{ org.org }}<br>({{ org.start }} - {{ org.end }})
                            </p>
                        </div>
                        <div class="right">
                            <p v-for="(org, index) in resume.orgs" v-if="index % 2 !== 0" :key="'right-' + index">
                                {{ org.org }}<br>({{ org.start }} - {{ org.end }})
                            </p>
                        </div>
                    </div>
                </div>
                <br v-if="resume.lang && resume.lang.length">
                <div v-if="resume.lang && resume.lang.length" class="languages">
                    <h4>Languages</h4>
                    <div class="languages-section">
                        <p v-for="(language, index) in resume.lang" :key="index">
                            {{ language.lang }} <br> <span>{{ language.lvl }}</span>
                        </p>
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

.fs-16{
    font-size: 16px;
}

.main {
    padding-top: 2mm;
    padding-bottom: 6mm;
}

.top {
    padding: 5mm 10mm;
    padding-bottom: 0px;
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
