<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div class="name-section">
          <div v-if="resume.name" class="name fw-semibold">{{ resume.name }} <span v-if="resume.lastName"> {{
          resume.lastName }}</span></div>
          <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
          <div v-if="resume.summary" class="bio" v-html="resume.summary"></div>
        </div>
        <div v-if="resume.phone || resume.email || resume.address || resume.web" class="details-section">
          <div class="detail-left">
            <p v-if="resume.email" class="details">
              <i class="icons bi-envelope-fill"></i>{{ resume.email }}
            </p>
            <p v-if="resume.address || resume.city || resume.country" class="details">
              <i class="icons bi-geo-alt-fill"></i>{{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}
            </p>
            <!-- <p class="details"><i class="icons bi-linkedin"></i>linkedin.com/in/john.lock</p> -->
          </div>
          <div class="detail-right">
            <p v-if="resume.phone" class="details">
              <i class="icons bi-phone-fill"></i>{{ resume.phone }}
            </p>
            <p v-if="resume.web" class="details">
              <i class="icons bi-browser-chrome"></i>{{ resume.web }}
            </p>
            <!-- <p class="details"><i class="icons bi-github"></i>github.com/john.lock</p> -->
          </div>
        </div>
        <div v-if="resume.skills?.length" class="skills">
          <h4 class="heading">
            <i class="heading-icons bi-mortarboard"></i>Skills
          </h4>
          <div class="skills-section">
            <div class="skill-section" v-for="(skill, i) in resume.skills" :key="'skill-' + i">
              {{ skill.skill || skill.skill_name }}
            </div>
          </div>
        </div>
      </div>

      <div class="line-section">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
      </div>

      <div class="middle">
        <div v-if="resume.employmentHistory?.length" class="work-experience">
          <h4 class="heading">
            <i class="heading-icons bi-backpack4"></i>Work Experience
          </h4>
          <div v-for="(exp, i) in resume.employmentHistory" :key="'exp-' + i">
            <h5>{{ exp.jobTitle || exp.role }}</h5>
            <h6>
              {{ exp.company }}
              <i class="bi bi-box-arrow-up-right"></i>
            </h6>
            <div class="date">
              <span>
                {{ exp.startYear || exp.startDate }} - {{ exp.endYear || exp.endDate || "Present" }}
              </span>
            </div>
            <p v-if="exp.description" v-html="exp.description"></p>
            <br>
          </div>
        </div>
        <div v-if="resume.education?.length">
          <div class="line-section">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
          </div>
          <div class="end">
            <div class="volunteer">
              <h4 class="heading">
                <i class="heading-icons bi-backpack4"></i>Education
              </h4>
              <div v-for="(edu, i) in resume.education" :key="'edu-' + i">
                <h5>{{ edu.degree }}</h5>
                <h6>{{ edu.university || edu.school }}</h6>
                <div class="date">
                  <span>
                    {{ edu.startYear || edu.startDate }} - {{ edu.endYear || edu.endDate || "Present" }}
                  </span>
                </div>
                <br>
              </div>
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
  --maincolor: #1b4f77;
  --backgroundcolor: #313c4e;
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
  padding: 2mm 10mm;
}

.top {
  padding: 5mm 0;
}

.top .name-section {
  line-height: 1.2;
  margin-bottom: 5mm;
}

.name-section .name {
  font-size: 30px !important;
  color: #313c4e;
}

.profession {
  font-size: 18px !important;
  color: #1b4f77
}

.bio {
  font-size: 14px !important;
  padding-top: 1mm;
}

.top .details-section {
  background-color: #313c4e;
  display: flex;
  color: #fff;
  gap: 200px;
  padding: 2mm 4mm;
  line-height: 2;
  border-radius: 5px;
}

.details-section .details {
  font-size: 12px !important;
  font-weight: 400;
}

.details .icons {
  padding-right: 8px;
  font-size: 12px !important;
}

.skills {
  padding-top: 4mm;
  line-height: 1.2;
}

.heading {
  font-size: 18px;
  text-transform: uppercase;
  padding-bottom: 3mm;
  color: #313c4e;
}

.heading .heading-icons {
  font-size: 15px;
  color: #fff;
  background-color: #313c4e;
  padding: 5px 6px;
  border-radius: 50%;
  margin-right: 4mm;
}

.skills-section {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  font-size: 14px;
}

.skill-section {
  width: 25%;
  line-height: 1.8;
}

.second-section {
  line-height: 1.8;
}

.third-section {
  line-height: 1.8;
}

.fourth-section {
  line-height: 1.8;
}

.line-section {
  padding: 2mm 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.line1 {
  background-color: #313c4e;
  height: 4px;
  width: 35%;
  border-radius: 5px 0 0 5px;
}

.line2 {
  background-color: rgb(16, 16, 185);
  height: 4px;
  width: 35%;
}

.line3 {
  background-color: rgb(170, 170, 223);
  height: 4px;
  width: 35%;
  border-radius: 0 5px 5px 0;
}

.middle {
  padding-top: 2mm;
}

.work-experience {
  line-height: 1.2;
}

.work-experience h5 {
  font-size: 16px;
}

.work-experience h6 {
  font-size: 16px;
  font-weight: 500;
}

.date {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  font-style: italic;
  color: #1b4f77;
  padding: 1mm 0;
}

.work-experience ul {
  font-size: 14px !important;
  padding-left: 4mm;
}

.work-experience p {
  font-size: 14px !important;
}

.work-experience li::marker {
  color: #1b4f77;
  font-size: 18px;
}

.end {
  padding-top: 2mm;
}

.volunteer {
  line-height: 1.2
}

.volunteer h5 {
  font-size: 16px;
}

.volunteer h6 {
  font-size: 16px;
  font-weight: 500;
}

.volunteer .date {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  font-style: italic;
  color: #1b4f77;
  padding: 1mm 0;
}

.volunteer ul {
  font-size: 14px !important;
  padding-left: 4mm;
}

.volunteer li::marker {
  color: #1b4f77;
  font-size: 18px;
}

.bi {
  font-size: 12px;
  color: #1b4f77;
  padding-left: 1mm;
}

@media print {

  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
