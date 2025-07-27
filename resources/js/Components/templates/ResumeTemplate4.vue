<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div class="name-section">
          <div v-if="resume.name" class="name">{{ resume.name + ' ' + resume.lastName }}</div>
          <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
          <div v-if="hasContactDetails" class="details">
            <p v-if="resume.email"><i class="icons bi-envelope-fill"></i>{{ resume.email }}</p>
            <p v-if="resume.phone"><i class="icons bi-phone-fill"></i> {{ resume.phone }}</p>
            <p v-if="resume.address"><i class="icons bi-geo-alt-fill"></i>{{ [resume.address, resume.city,
            resume.postalCode,
            resume.country].filter(Boolean).join(', ') }}</p>
          </div>
        </div>
        <div v-if="resume.profile" class="profile">
          <div class="profile-image">
            <img :src="resume.profile" alt="">
          </div>
        </div>
      </div>

      <br>

      <div class="middle">
        <div class="middl-left">
          <div v-if="resume.employmentHistory?.length" class="experience">
            <h4>Experience</h4>
            <div v-for="(exp, i) in resume.employmentHistory" :key="i">
              <h5>{{ exp.jobTitle }}</h5>
              <div class="experience-top mb-2">
                <div class="experience-left">
                  <h6>{{ exp.company }}</h6>
                </div>
                <div class="experience-right">
                  <p><i class="bi bi-calendar-check"></i> {{ exp.startDate }} - {{ exp.endDate }}</p>
                  <p><i class="bi bi-geo-alt-fill"></i> {{ exp.city }}</p>
                </div>
              </div>
              <div v-html="exp.description"></div>
            </div>
          </div>

          <br v-if="resume.experiences?.length || resume.education?.length">

          <div v-if="resume.education && resume.education.length" class="education">
            <h4>Education</h4>
            <div class="mb-3" v-for="(edu, index) in resume.education" :key="'edu-' + index">
              <h5>{{ edu.degree }}</h5>
              <div class="education-top">
                <div class="education-left">
                  <h6>{{ edu.school }}</h6>
                </div>
                <div class="education-right">
                  <p><i class="bi bi-calendar-check"></i> {{ edu.startDate }} - {{ edu.endDate }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="middle-right">
          <div v-if="resume.summary" class="summary">
            <h4>Summary</h4>
            <p v-html="resume.summary"></p>
          </div>

          <br>

          <div v-if="resume.skills && resume.skills.length" class="skills">
            <h4>Skills</h4>
            <div class="skills-section">
              <p>
                <span v-for="(skill, index) in resume.skills" :key="'skill-' + index">{{ skill.skill }}</span>
              </p>
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
  return props.resume.email || props.resume.phone || props.resume.address
})

const getLangLevel = (level) => {
  if (level === 'Beginner') return 1;
  if (level === 'Intermediate') return 3;
  if (level === 'Expert') return 5;
  return 0;
};
</script>

<style scoped>
:root {
  --headingcolor: #384347;
  --textcolor: #008cff;
  --backgroundcolor: #22405c;
}

* {
  margin: 0;
  padding: 0;
  font-family: "DM Sans", sans-serif;
  font-size: 14px;
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
  display: flex;
  justify-content: space-between;
}

.name-section {
  padding: 2mm 0;
  max-width: 70%;
}

.name-section .name {
  font-size: 25px;
  text-transform: uppercase;
  color: #384347;
  font-weight: bold;
}

.name-section .profession {
  font-size: 18px;
  padding: 2px 0;
  color: #008cff;
}

.details {
  display: flex;
  gap: 2mm;
}

.details p {
  font-size: 11px;
}

.details .icons {
  padding-right: 5px;
  font-size: 12px;
}

.profile {
  max-width: 30%;
}

.profile-image {
  text-align: right;
  width: 210px;
  height: 100px;
  border-radius: 50px;
  margin-right: 5mm;
}

.profile-image img {
  height: 100%;
  border-radius: 50px;
}

.middle {
  display: flex;
  justify-content: space-between;
  gap: 10mm;
}

.middl-left {
  max-width: 60%;
  width: 100%;
}

.experience {
  line-height: 1.2;
  padding-top: 1mm;
}

.experience h4 {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 400;
  border-bottom: 1px solid #000000;
  padding-bottom: 1mm;
}

.experience h5 {
  font-size: 16px;
  font-weight: 400;
  padding-top: 3mm;
}

.experience-top {
  display: flex;
  justify-content: space-between;
  padding: 1mm 0;
  line-height: 1.4;
}

.experience-left h6 {
  font-size: 15px;
  font-weight: 400;
  color: #008cff;
}

.experience-right {
  width: 65%;
  display: flex;
  text-align: right;
  justify-content: end;
  gap: 3mm;
}

.experience-right p {
  font-size: 13px;
}

.experience-right p .bi {
  font-size: 12px;
}

.experience p {
  font-size: 13px;
}

.experience ul {
  padding-left: 5mm;
  font-size: 13px;
}

.education h4 {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 400;
  border-bottom: 1px solid #000000;
  padding-bottom: 1mm;
}

.education-top {
  display: flex;
  justify-content: space-between;
  padding: 2px 0;
  line-height: 1.4;
}

.education h5 {
  font-size: 15px;
  font-weight: 400;
  padding-top: 1mm;
}

.education-left h6 {
  font-size: 15px;
  font-weight: 400;
  color: #008cff;
}

.education-right {
  text-align: right;
}

.education-right p {
  font-size: 13px;
}

.education-right p .bi {
  font-size: 12px;
}

.middle-right {
  max-width: 40%;
  width: 100%;
}

.summary {
  line-height: 1.2;
}

.summary h4 {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 400;
  color: #384347;
  border-bottom: 1px solid #384347;
  padding: 1mm 0;
}

.summary p {
  margin-top: 2mm;
  font-size: 13px;
}

.skills h4 {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 400;
  border-bottom: 1px solid #000;
  padding-bottom: 1mm;
}

.skills p {
  font-size: 13px;
  display: flex;
  flex-wrap: wrap;
}

.skills p span {
  margin-top: 10px;
  margin-right: 3mm;
  padding: 2px;
  border-bottom: 1px solid #000;
}

.languages h4 {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 400;
  color: #384347;
  border-bottom: 1px solid #384347;
  padding: 1mm 0;
}

.languages-section {
  display: flex;
  justify-content: space-between;
}

.languages-left h6 {
  font-size: 15px;
  font-weight: 400;
  padding: 2mm 0 1mm 0;
}

.languages-right {
  display: flex;
  align-items: center;
  gap: 5mm;
}

.languages-right p {
  font-size: 13px;
}

.div-section {
  display: flex;
  gap: 1mm;
}

.div-section div {
  width: 8px;
  height: 8px;
  background-color: #000;
  border-radius: 50%;
}

.passions h4 {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 400;
  color: #384347;
  border-bottom: 1px solid #384347;
  padding: 1mm 0;
  margin-bottom: 2mm;
}

.passions h6 {
  font-size: 15px;
  font-weight: 400;
  padding: 3mm 0;
}

.passions h6 .bi {
  padding: 8px 10px;
  background-color: #cfcfcf;
  border-radius: 50%;
  font-size: 10px;
  margin-right: 2mm;
}

@media print {

  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>