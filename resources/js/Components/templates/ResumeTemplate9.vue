<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div v-if="resume.name" class="name fw-semibold">{{ resume.name }} <span v-if="resume.lastName"> {{
          resume.lastName }}</span></div>
        <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
        <div v-if="resume.phone || resume.email || resume.address || resume.web" class="details">
          <p v-if="resume.phone">{{ resume.phone }}</p>
          <p v-if="resume.email">{{ resume.email }}</p>
          <p v-if="resume.linkedin">{{ resume.linkedin }}</p>
          <p v-if="resume.address || resume.city || resume.country">{{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}</p>
        </div>
      </div>
      <div class="middle">
        <div v-if="resume.summary" class="summary">
          <h4 class="fw-semibold">Summary</h4>
          <p v-html="resume.summary"></p>
        </div>
        <br v-if="resume.summary || resume.employmentHistory?.length">
        <div v-if="resume.employmentHistory?.length" class="experience">
          <h4 class="fw-semibold">Experience</h4>
          <div v-for="(exp, i) in resume.employmentHistory" :key="'exp-' + i">
            <div class="experience-section">
              <div class="experience-left">
                <h5>{{ exp.role || exp.jobTitle }}</h5>
                <h6>{{ exp.company }}</h6>
              </div>
              <div class="experience-right">
                <p v-if="exp.address">{{ exp.address }}</p>
                <p>
                  {{ exp.startYear || exp.startDate }} - {{ exp.endYear || exp.endDate || "Present" }}
                </p>
              </div>
            </div>
            <p v-if="exp.description" v-html="exp.description"></p>
          </div>
        </div>
        <br v-if="resume.projects?.length">
        <div v-if="resume.projects?.length" class="project">
          <h4>Projects</h4>
          <div v-for="(project, i) in resume.projects" :key="'project-' + i">
            <h5>{{ project.title }}</h5>
            <p>{{ project.desc }}</p>
          </div>
        </div>
        <br v-if="resume.languages?.length">
        <div v-if="resume.languages?.length" class="language">
          <h4>Languages</h4>
          <div class="language-section">
            <p v-for="(language, i) in resume.languages" :key="'lang-' + i">
              {{ language.lang }}<span v-if="language.lvl">({{ language.lvl }})</span>
            </p>
          </div>
        </div>
        <br v-if="resume.education?.length">
        <div v-if="resume.education?.length" class="education">
          <h4 class="fw-semibold">Education</h4>
          <div v-for="(edu, i) in resume.education" :key="'edu-' + i" class="education-section">
            <div class="education-left">
              <h5>{{ edu.degree }}</h5>
              <p>{{ edu.university || edu.school }}</p>
            </div>
            <div class="education-right">
              <p v-if="edu.city">{{ edu.city }}</p>
              <p>
                {{ edu.startYear || edu.startDate }} - {{ edu.endYear || edu.endDate || "Present" }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  resume: { type: Object, required: true }
});
</script>

<style scoped>
:root {
  --textcolor: rgb(35, 0, 131);
  --secoundtextcolor: rgb(75, 168, 255);
}

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
  padding-bottom: 5mm;
}

.top .name {
  font-size: 25px !important;
}

.top .profession {
  font-size: 18px;
  color: rgb(35, 0, 131);
}

.details {
  display: flex;
  align-items: center;
  gap: 5mm;
}

.details p {
  font-size: 13px;
  padding-top: 1mm;
}

.middle {
  padding-top: 2mm;
}

.summary h4 {
  font-size: 17px;
  text-transform: uppercase;
  color: #535353;
  padding-bottom: 1mm;
}

.summary p {
  font-size: 13px;
}

.experience h4 {
  font-size: 17px;
  text-transform: uppercase;
  color: #535353;
}

.experience-section {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.experience-section h5 {
  font-size: 16px;
  font-weight: 400;
  padding-top: 2mm;
  color: rgb(35, 0, 131);
}

.experience-section h6 {
  font-size: 15px;
  color: rgb(75, 168, 255);
  font-weight: 400;
}

.experience-section p {
  font-size: 13px;
  padding: 1px 0;
}

.experience ul {
  font-size: 13px;
  padding-left: 5mm;
  padding-bottom: 2mm;
}

.experience p {
  font-size: 13px;
  padding-bottom: 2mm;
}

.achievement h4 {
  font-size: 17px;
  text-transform: uppercase;
  color: #535353;
}

.achievement-section {
  display: flex;
  gap: 5mm;
}

.achievement-section .left {
  max-width: 50%;
}

.left h6 {
  font-size: 15px;
  color: rgb(75, 168, 255);
  font-weight: 400;
}

.left p {
  font-size: 13px;
  padding: 1px 0;
}

.achievement-section .right {
  max-width: 50%;
}

.right h6 {
  font-size: 15px;
  color: rgb(75, 168, 255);
  font-weight: 400;
}

.right p {
  font-size: 13px;
  padding: 1px 0;
}

.project h4 {
  font-size: 17px;
  text-transform: uppercase;
  color: #535353;
}

.project h5 {
  font-size: 16px;
  font-weight: 400;
  padding-top: 2mm;
  color: rgb(35, 0, 131);
}

.project p {
  font-size: 13px;
  padding: 1px 0;
}

.language h4 {
  font-size: 17px;
  text-transform: uppercase;
  color: #535353;
}

.language-section {
  display: flex;
  gap: 25mm;
  align-items: center;
  padding-top: 2mm;
}

.language-section p {
  font-size: 14px;
  font-weight: 600;
}

.language-section p span {
  font-size: 14px;
  font-weight: 400;
  padding-left: 2mm;
}

.education h4 {
  font-size: 17px;
  text-transform: uppercase;
  color: #535353;
}

.education-section {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.education-section h5 {
  font-size: 16px;
  font-weight: 400;
  padding-top: 2mm;
  color: rgb(35, 0, 131);
}

.education-section h6 {
  font-size: 15px;
  color: rgb(75, 168, 255);
  font-weight: 400;
}

.education-section p {
  font-size: 13px;
  padding: 1px 0;
}

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
