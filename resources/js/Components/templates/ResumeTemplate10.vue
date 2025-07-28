<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div class="name-section">
          <div v-if="resume.name" class="name">{{ resume.name }}</div>
          <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
        </div>
        <div class="bio-section" v-if="resume.summary">
          <div class="bio" v-html="resume.summary"></div>
        </div>
        <div
          v-if="resume.phone || resume.email || resume.address || resume.web"
          class="details-section"
        >
          <div class="detail-left">
            <p v-if="resume.email" class="details">
              <i class="icons bi-envelope-fill"></i>{{ resume.email }}
            </p>
            <p v-if="resume.address || resume.city || resume.postalCode || resume.country" class="details">
              <i class="icons bi-geo-alt-fill"></i>
              {{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}
            </p>
            <p v-if="resume.linkedin" class="details">
              <i class="icons bi-linkedin"></i>{{ resume.linkedin }}
            </p>
          </div>
          <div class="detail-right">
            <p v-if="resume.phone" class="details">
              <i class="icons bi-phone-fill"></i>{{ resume.phone }}
            </p>
            <p v-if="resume.web" class="details">
              <i class="icons bi-link-45deg"></i>{{ resume.web }}
            </p>
            <p v-if="resume.github" class="details">
              <i class="icons bi-github"></i>{{ resume.github }}
            </p>
          </div>
        </div>
      </div>
      <div class="middle">
        <div class="middle-left">
          <div v-if="resume.employmentHistory && resume.employmentHistory.length" class="work-experience">
            <h4 class="heading">
              <i class="heading-icons bi-backpack4"></i>Work Experience
            </h4>
            <div v-for="(exp, i) in resume.employmentHistory" :key="'exp-' + i">
              <h5>{{ exp.role || exp.jobTitle }}</h5>
              <h6>
                {{ exp.company }}
                <i class="bi bi-box-arrow-up-right"></i>
              </h6>
              <div class="date">
                <span>{{ exp.startYear || exp.startDate }} - {{ exp.endYear || exp.endDate || "Present" }}</span>
                <span>{{ exp.address }}</span>
              </div>
              <p v-if="exp.description" v-html="exp.description"></p>
            </div>
          </div>
          <br v-if="(resume.employmentHistory && resume.employmentHistory.length) || (resume.education && resume.education.length)" />
          <div v-if="resume.education && resume.education.length" class="education">
            <h4 class="heading">
              <i class="heading-icons bi-mortarboard"></i>Education
            </h4>
            <div v-for="(edu, i) in resume.education" :key="'edu-' + i">
              <h5>{{ edu.degree }}</h5>
              <h6>{{ edu.university || edu.school }}</h6>
              <div class="date">
                <span>{{ edu.startYear || edu.startDate }} - {{ edu.endYear || edu.endDate || "Present" }}</span>
              </div>
              <br />
            </div>
          </div>
        </div>
        <div class="middle-right">
          <div v-if="resume.skills && resume.skills.length" class="skills">
            <h4 class="heading">
              <i class="heading-icons bi-wrench-adjustable-circle"></i>Skills
            </h4>
            <p>
              <span v-for="(skill, index) in resume.skills" :key="index">{{ skill.skill }}</span>
            </p>
          </div>
          <br v-if="resume.skills && resume.skills.length" />
          <div v-if="resume.cert && resume.cert.length" class="certificates">
            <h4 class="heading">
              <i class="heading-icons bi-bookmark-star"></i>certificates
            </h4>
            <p v-for="(certificate, i) in resume.cert" :key="i">
              {{ certificate.certificate_name }}
              <i class="bi bi-box-arrow-up-right"></i>
            </p>
          </div>
          <br v-if="resume.cert && resume.cert.length" />
          <!--
          <div class="personal-project">
            <h4 class="heading"><i class="heading-icons bi-laptop"></i>Personal Project</h4>
            <h6>Personal website & Portfolio (2015 - Present) <i class="bi bi-box-arrow-up-right"></i></h6>
            <ul>
              <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias laborum veniam omnis amet praesentium dolore vitae, non incidunt sit minima.</li>
            </ul>
          </div>
          <br>
          -->
          <div v-if="resume.lang && resume.lang.length" class="languages">
            <h4 class="heading">
              <i class="heading-icons bi-translate"></i>Languages
            </h4>
            <h6 v-for="(language, i) in resume.lang" :key="i">
              {{ language.lang }} <br />
              <span>{{ language.lvl }}</span>
            </h6>
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
@import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

:root {
  --maincolor: #1d3344;
  --secondcolor: #F3B943;
}

* {
  margin: 0;
  padding: 0;
  font-family: 'Ubuntu', sans-serif;
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
  padding: 2mm;
}

.name-section {
  line-height: 1.2;
  padding: 2mm 8mm;
}

.name-section .name {
  font-size: 30px !important;
}

.name-section .profession {
  font-size: 18px !important;
  color: #F3B943;
}

.bio-section {
  background-color: #1d3344;
  padding: 4mm 8mm;
  line-height: 1.2;
  border-radius: 5px 5px 0 0;
  color: #fff;
}

.bio-section .bio {
  font-size: 14px !important;
}

.details-section {
  background-color: #F3B943;
  color: #fff;
  display: flex;
  gap: 200px;
  padding: 2mm 8mm;
  line-height: 2;
  border-radius: 0 0 5px 5px;
}

.details-section .details {
  font-size: 12px !important;
  font-weight: 400;
}

.details .icons {
  padding-right: 8px;
  font-size: 12px !important;
}

.middle {
  display: flex;
  justify-content: space-between;
  gap: 2mm;
  padding: 2mm 0mm 2mm 6mm;
}

.middle-left {
  width: 60%;
  padding-right: 4px;
}

.work-experience {
  padding-top: 2mm;
  line-height: 1.2;
}

.heading {
  font-size: 18px;
  text-transform: uppercase;
  padding-bottom: 3mm;
  color: #1d3344;
}

.heading .heading-icons {
  font-size: 15px;
  color: #fff;
  background-color: #1d3344;
  padding: 5px 6px;
  border-radius: 50%;
  margin-right: 2mm;
}

.work-experience h5 {
  font-size: 16px;
}

.work-experience h6 {
  font-size: 15px;
  font-weight: 500;
}

.date {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  font-style: italic;
  color: #F3B943;
  padding: 1mm 0;
}

.work-experience ul {
  font-size: 14px !important;
  padding-left: 4mm;
  line-height: 1.3;
}

.work-experience p {
  font-size: 14px !important;
  padding-bottom: 4mm;
}

.work-experience li::marker {
  color: #F3B943;
}

.education {
  line-height: 1.2;
}

.education h5 {
  font-size: 16px;
}

.education h6 {
  font-size: 16px;
  font-weight: 500;
}

.bi {
  font-size: 12px;
  color: #F3B943;
  padding-left: 1mm;
}

.middle-right {
  width: 40%;
  background-color: #ebebeb;
  padding: 4mm 8mm 4mm 4mm;
  border-radius: 5px;
}

.skills {
  line-height: 2;
}

.skills p {
  font-size: 14px;
  color: #fff;
}

.skills p span {
  background-color: rgb(87, 128, 129);
  padding: 0px 5px;
  margin: 3px;
  border-radius: 5px;
  display: inline-block;
}

.certificates {
  line-height: 1.2;
}

.certificates p {
  font-size: 14px;
  padding: 1mm 0;
}

.certificates p span {
  font-size: 12px;
  font-style: italic;
  color: #244d50;
}

.personal-project {
  line-height: 1.2;
}

.personal-project h6 {
  font-size: 15px;
  font-weight: 500;
  padding-top: 1mm;
}

.personal-project ul {
  font-size: 13px !important;
  padding-left: 5mm;
  line-height: 1.3;
}

.personal-project li::marker {
  color: #F3B943;
}

.languages {
  line-height: 1.2;
}

.languages h6 {
  font-size: 14px;
  font-weight: 500;
  padding: 2mm 0;
}

.languages h6 span {
  font-size: 12px;
  font-style: italic;
}

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
