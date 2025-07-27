<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div v-if="resume.name" class="name fw-semibold">{{ resume.name }} <span v-if="resume.lastName"> {{ resume.lastName }}</span></div>
        <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
        <div v-if="hasContactDetails" class="details">
          <p v-if="resume.phone"><i class="icons bi-telephone-fill"></i> {{ resume.phone }}</p>
          <p v-if="resume.email"><i class="icons bi-envelope-fill"></i> {{ resume.email }}</p>
          <p v-if="resume.address || resume.city || resume.country">
            <i class="icons bi-geo-alt-fill"></i>
            {{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}
          </p>
        </div>
      </div>

      <br v-if="resume.employmentHistory?.length">

      <div v-if="resume.employmentHistory?.length" class="experience">
        <h4>Experience</h4>
        <div v-for="(exp, i) in resume.employmentHistory" :key="'exp-' + i">
          <h5>{{ exp.jobTitle }}</h5>
          <h6>{{ exp.company }}</h6>
          <div class="experience-section">
            <p v-if="exp.startDate"><i class="bi bi-calendar-check"></i> {{ exp.startDate }} - {{ exp.endDate || "Present"  }}</p>
            <p v-if="exp.city"><i class="bi bi-geo-alt-fill"></i> {{ exp.city }}</p>
          </div>
          <div class="exp-desc" v-html="exp.description"></div>
        </div>
      </div>

      <br v-if="resume.employmentHistory?.length || resume.education?.length">

      <div v-if="resume.education?.length" class="education">
        <h4>Education</h4>
        <div v-for="(edu, i) in resume.education" :key="'edu-' + i">
          <h5>{{ edu.degree }}</h5>
          <h6>{{ edu.school || edu.university }}</h6>
          <div class="education-section">
            <p v-if="edu.startDate"><i class="bi bi-calendar-check"></i> {{ edu.startDate }} - {{ edu.endDate || "Present" }}</p>
            <p v-if="edu.city || edu.address"><i class="bi bi-geo-alt-fill"></i> {{ edu.city || edu.address }}</p>
          </div>
        </div>
      </div>

      <br v-if="resume.skills?.length">

      <div v-if="resume.skills?.length" class="skills">
        <h4>Skills</h4>
        <p>
          <span v-for="(skill, i) in resume.skills" :key="'skill-' + i">{{ skill.skill }}</span>
        </p>
      </div>

      <br v-if="resume.languages?.length">

      <div v-if="resume.languages?.length" class="language">
        <h4>Languages</h4>
        <div class="language-section">
          <div class="sections" v-for="(lang, i) in resume.languages" :key="'lang-' + i">
            <div class="info">
              <p><span>{{ lang.lang }}</span></p>
              <p>{{ lang.lvl }}</p>
            </div>
            <div class="bar">
              <span :class="getBarClass(lang.lvl)"></span>
            </div>
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
});

const hasContactDetails = computed(() => {
  return props.resume.phone || props.resume.email || props.resume.address || props.resume.city || props.resume.country;
});

function getBarClass(level) {
  if (level === 'Beginner') return 'greek';
  if (level === 'Intermediate') return 'german';
  if (level === 'Expert') return 'english';
  return '';
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Open+Sans:ital@0;1&family=PT+Serif&display=swap");

* {
  margin: 0;
  padding: 0;
  font-family: "DM Sans", sans-serif;
}

.page {
  background: #fff;
  width: 210mm;
  min-height: 297mm;
  margin: 10mm auto;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

.main {
  padding: 10mm;
}

.top {
  padding: 1mm 0;
}

.top .name {
  font-size: 25px !important;
  text-transform: uppercase;
  font-weight: bold;
}

.top .profession {
  font-size: 16px !important;
  color: rgb(1, 1, 255);
}

.details {
  display: flex;
  align-items: center;
  gap: 15mm;
  padding-top: 1mm;
  padding-bottom: 1mm;
}

.details p {
  font-size: 13px !important;
}

.icons {
  padding-right: 2mm;
  font-size: 12px;
  color: rgb(1, 1, 255);
}

.top p {
  font-size: 13px;
}

.experience h4 {
  font-size: 17px !important;
  text-transform: uppercase;
  border-bottom: 2px solid #000000;
  padding-bottom: 1mm;
  font-family: "PT Serif", serif;
}

.experience h5 {
  font-size: 16px !important;
  padding: 1mm 0;
  font-family: "PT Serif", serif;
}

.experience h6 {
  font-size: 14px !important;
  color: rgb(1, 1, 255);
}

.experience p {
  font-size: 14px;
  padding-bottom: 2mm;
}

.experience ul {
  font-size: 14px !important;
  padding-left: 5mm;
}

.experience-section {
  display: flex;
  gap: 5mm;
}

.experience-section p {
  font-size: 13px !important;
}

.experience-section .bi {
  font-size: 11px !important;
  padding-right: 1mm;
}

.exp-desc{
    border-bottom: 1px dashed #9e9c9c;
    margin-bottom: 10px;
}

.education h4 {
  font-size: 17px !important;
  text-transform: uppercase;
  border-bottom: 2px solid #000000;
  padding-bottom: 1mm;
  font-family: "PT Serif", serif;
}

.education h5 {
  font-size: 16px;
  padding: 1mm 0;
  font-family: "PT Serif", serif;
}

.education h6 {
  font-size: 14px !important;
  color: rgb(1, 1, 255);
}

.education p {
  font-size: 13px !important;
  padding-top: 1mm;
}

.education-section {
  display: flex;
  gap: 5mm;
  padding-bottom: 2mm;
}

.education-section p {
  font-size: 13px !important;
}

.education-section .bi {
  font-size: 11px;
  padding-right: 1mm;
}

.achievement h4 {
  font-size: 17px !important;
  text-transform: uppercase;
  border-bottom: 2px solid #000000;
  padding-bottom: 1mm;
  font-family: "PT Serif", serif;
}

.achievement-section {
  display: flex;
  justify-content: space-between;
  gap: 5mm;
}

.achievement h6 {
  font-size: 15px !important;
  padding-top: 2mm;
}

.achievement .bi {
  font-size: 16px !important;
  padding-right: 2mm;
  color: rgb(1, 1, 255);
}

.achievement p {
  font-size: 13px !important;
  padding-left: 6mm;
}

.skills h4 {
  font-size: 17px !important;
  text-transform: uppercase;
  border-bottom: 2px solid #000000;
  padding-bottom: 1mm;
  font-family: "PT Serif", serif;
}

.skills p {
  font-size: 13px !important;
  padding-top: 1mm;
  padding-bottom: 2mm;
}

.skills p span {
  line-height: 2.3;
  margin-right: 3mm;
  padding: 3px 5px;
  border-bottom: 2px solid #525252;
}

.language h4 {
  font-size: 17px !important;
  text-transform: uppercase;
  border-bottom: 2px solid #000000;
  padding-bottom: 1mm;
  font-family: "PT Serif", serif;
}

.language-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.sections {
  padding: 1mm 0;
  max-width: 30%;
}

.info {
  display: flex;
  gap: 25mm;
  padding: 2mm 0;
}

.sections .info p {
  font-size: 14px !important;
}

.sections .info p span {
  font-weight: bold;
}

.sections .bar {
  width: 100%;
  height: 7px;
  position: relative;
  background-color: #cfcfcf;
  border-radius: 10px;
  margin-top: 1mm;
}

.english {
  width: 100%;
  height: 100%;
  position: absolute;
  background: rgb(1, 1, 255);
  border-radius: 10px;
}

.german {
  width: 80%;
  height: 100%;
  position: absolute;
  background: rgb(1, 1, 255);
  border-radius: 10px;
}

.greek {
  width: 55%;
  height: 100%;
  position: absolute;
  background: rgb(1, 1, 255);
  border-radius: 10px;
}

.ul {
  padding-bottom: 2mm;
  border-bottom: 1px dashed #aeafaf;
}

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
