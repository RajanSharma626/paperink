<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div v-if="resume.name" class="name fw-semibold">{{ resume.name }} <span v-if="resume.lastName"> {{
          resume.lastName }}</span></div>
        <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
      </div>
      <div class="middle">
        <div class="middle-left">
          <div v-if="resume.summary" class="summary">
            <p v-html="resume.summary"></p>
          </div>

          <div v-if="resume.employmentHistory?.length" class="experience">
            <h4>Experience</h4>
            <div v-for="(exp, i) in resume.employmentHistory" :key="'exp-' + i" class="experience-section">
              <div class="left-section" v-if="exp.startDate">
                <h6>
                  {{ exp.startDate }} - {{ exp.endDate || "Present" }}
                </h6>
              </div>
              <div class="right-section">
                <h5>{{ exp.jobTitle || exp.role }}</h5>
                <span>{{ [exp.company, exp.city,].filter(Boolean).join(', ') }}</span>
                <p v-if="exp.description" v-html="exp.description"></p>
              </div>
            </div>
          </div>

          <div v-if="resume.education?.length" class="education">
            <h4>Education</h4>
            <div v-for="(edu, i) in resume.education" :key="'edu-' + i" class="education-section">
              <div class="left-section" v-if="edu.startYear || edu.startDate">
                <h6>
                  {{ edu.startYear || edu.startDate }} - {{ edu.endYear || edu.endDate || "Present" }}
                </h6>
              </div>
              <div class="right-section">
                <h5 class="mb-2">{{ [edu.degree, edu.school,].filter(Boolean).join(', ') }}</h5>
                <div style="font-size: 12px;" v-if="edu.description" v-html="edu.description"></div>
              </div>
            </div>
          </div>

          <div v-if="resume.certificates?.length" class="certificate">
            <h4>Certificates</h4>
            <p v-for="(certificate, i) in resume.certificates" :key="'cert-' + i">
              {{ certificate.certificate_name }}
            </p>
          </div>
        </div>
        <div class="middle-right">
          <div v-if="resume.phone || resume.email || resume.address || resume.city || resume.country || resume.web"
            class="personal-info">
            <h4>Personal Info</h4>
            <div class="info-section">
              <template v-if="resume.address || resume.city || resume.country || resume.postalCode">
                <h6>Address</h6>
                <p>{{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}</p>
              </template>
              <template v-if="resume.phone">
                <h6>Phone</h6>
                <p>{{ resume.phone }}</p>
              </template>
              <template v-if="resume.email">
                <h6>E-mail</h6>
                <p>{{ resume.email }}</p>
              </template>
              <!--
              <h6>LinkedIn</h6>
              <p>linkedin.com/justin01</p>
              -->
            </div>
          </div>
          <div v-if="resume.skills?.length" class="skills">
            <h4>Skills</h4>
            <div class="skills-section">
              <p v-for="(skill, i) in resume.skills" :key="'skill-' + i">
                <span>
                  {{ skill.skill || skill.skill_name }}
                </span>
              </p>
            </div>
          </div>
          <!--
          <div class="software">
            <h4>Software</h4>
            <div class="software-section">
              <div class="left-section">
                <p>Microsoft Project</p>
                <div class="bar">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="right-section">
                <p>Excellent</p>
              </div>
            </div>
            <div class="software-section">
              <div class="left-section">
                <p>Windowa Server</p>
                <div class="bar">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                  <div1></div1>
                </div>
              </div>
              <div class="right-section">
                <p>Very Good</p>
              </div>
            </div>
            <div class="software-section">
              <div class="left-section">
                <p>Linux/Unix</p>
                <div class="bar">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                  <div1></div1>
                </div>
              </div>
              <div class="right-section">
                <p>Very Good</p>
              </div>
            </div>
            <div class="software-section">
              <div class="left-section">
                <p>Microsoft Excel</p>
                <div class="bar">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div1></div1>
                  <div1></div1>
                </div>
              </div>
              <div class="right-section">
                <p>Good</p>
              </div>
            </div>
          </div>
          -->
          <div v-if="resume.languages?.length" class="language">
            <h4>Languages</h4>
            <div v-for="(lang, i) in resume.languages" :key="'lang-' + i" class="language-section">
              <div class="left-section">
                <p>{{ lang.lang }}</p>
                <div class="bar">
                  <template v-if="lang.lvl === 'Beginner'">
                    <div></div>
                    <div1></div1>
                    <div1></div1>
                    <div1></div1>
                    <div1></div1>
                  </template>
                  <template v-else-if="lang.lvl === 'Intermediate'">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div1></div1>
                    <div1></div1>
                  </template>
                  <template v-else-if="lang.lvl === 'Expert'">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                  </template>
                </div>
              </div>
              <div class="right-section">
                <p>{{ lang.lvl }}</p>
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
@import url("https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Lato&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=PT+Serif&family=Rubik&display=swap");

* {
  margin: 0;
  padding: 0;
  font-family: "Open Sans", sans-serif;
}

.page {
  background: #fff;
  width: 210mm;
  min-height: 297mm;
  margin: 10mm auto;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

.main .top {
  padding: 5mm;
  background-color: #2f2f2f;
  color: #fff;
  border-radius: 10px 10px 0 0;
}

.name {
  font-size: 25px !important;
  font-weight: 600;
}

.profession {
  font-size: 17px !important;
}

.middle {
  display: flex;
}

.middle-left {
  width: 70%;
  padding: 5mm;
  line-height: 1.5;
}

.summary {
  padding-bottom: 2mm;
}

.summary p {
  font-size: 12px !important;
}

/* ----------------------------------Experience---------------------------------------- */

.experience {
  padding: 2mm 0;
}

.experience h4 {
  font-size: 16px !important;
  border-bottom: 1px solid #cfcfcf;
  padding-bottom: 2mm;
}

.experience-section {
  display: flex;
  gap: 2mm;
  padding-top: 3mm;
  padding-bottom: 2mm;
}

.experience-section .left-section {
  width: 15%;
  padding-top: 3px;
}

.experience-section .left-section h6 {
  font-size: 11px !important;
}

.experience-section .right-section {
  width: 85%;
}

.experience-section .right-section h5 {
  font-size: 15px !important;
}

.experience-section .right-section span {
  font-size: 12px !important;
  font-style: italic;
}

.experience-section .right-section ul {
  font-size: 12px !important;
  padding-left: 4mm;
}

.experience-section .right-section p {
  font-size: 12px !important;
  padding-bottom: 1mm;
}

.experience-section .right-section ul li::marker {
  font-size: 10px !important;
}

/* -------------------------------Education---------------------------------- */

.education {
  padding: 2mm 0;
}

.education h4 {
  font-size: 16px !important;
  border-bottom: 1px solid #cfcfcf;
  padding-bottom: 2mm;
}

.education-section {
  display: flex;
  gap: 2mm;
  padding-top: 3mm;
  padding-bottom: 2mm;
}

.education-section .left-section {
  width: 15%;
  padding-top: 3px;
}

.education-section .left-section h6 {
  font-size: 11px !important;
}

.education-section .right-section {
  width: 85%;
}

.education-section .right-section h5 {
  font-size: 15px !important;
}

.education-section .right-section span {
  font-size: 12px !important;
  font-style: italic;
}

.education-section .right-section ul {
  font-size: 12px !important;
  padding-left: 4mm;
}

.education-section .right-section ul li::marker {
  font-size: 10px !important;
}

/* ---------------------------Certifications--------------------------------------------- */

.certificate {
  padding: 2mm 0;
}

.certificate h4 {
  color: var(--textcolor);
  border-top: 1px solid var(--textcolor);
  border-bottom: 1px solid var(--textcolor);
  padding: 1mm 0;
  font-size: 16px;
  margin-bottom: 1mm;
}

.certificate p {
  font-size: 13px;
  padding-top: 2mm;
  padding-left: 17%;
}

/* -----------------------RIGHT SECTION----------------------- */

.middle-right {
  width: 30%;
  padding: 5mm;
  min-height: 100%;
  background-color: #e4efd8;
  line-height: 1.5;
  border-radius: 0 0 10px 0;
}

.personal-info {
  padding: 2mm 0;
}

.personal-info h4 {
  font-size: 16px !important;
  border-bottom: 1px solid #cfcfcf;
  padding-bottom: 2mm;
}

.info-section h6 {
  padding: 2mm 0 1mm 0;
  font-size: 13px;
}

.info-section p {
  font-size: 12px !important;
}

/* ------------------------------SKills------------------------------------------------- */

.skills {
  padding: 3mm 0;
}

.skills h4 {
  font-size: 16px !important;
  border-bottom: 1px solid #cfcfcf;
  padding-bottom: 2mm;
  margin-bottom: 2mm;
}

.skills-section p {
  font-size: 12px !important;
  line-height: 2;
}

/* ----------------------------------Software------------------------------------------------------- */

.software {
  padding: 2mm 0;
}

.software h4 {
  font-size: 16px !important;
  border-bottom: 1px solid #cfcfcf;
  padding-bottom: 2mm;
  margin-bottom: 2mm;
}

.software-section {
  padding: 1mm 0;
}

.software-section .left-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.software-section .left-section p {
  font-size: 13px !important;
}

.software-section .left-section .bar {
  display: flex;
  gap: 1mm;
  align-items: center;
}

.bar div {
  height: 10px;
  width: 10px;
  border-radius: 50%;
  background-color: #000;
}

.bar div1 {
  height: 10px;
  width: 10px;
  border-radius: 50%;
  background-color: #949494;
}

.software-section .right-section {
  padding-top: 2px;
  max-width: 100%;
}

.software-section .right-section p {
  font-size: 13px !important;
  text-align: right;
}

/* --------------------------------Languages-------------------------------------------- */

.language {
  padding: 2mm 0;
}

.language h4 {
  font-size: 16px !important;
  border-bottom: 1px solid #cfcfcf;
  padding-bottom: 2mm;
  margin-bottom: 2mm;
}

.language .bi {
  padding: 3px 6px;
  background-color: var(--textcolor);
  border-radius: 50%;
  color: #fff;
  margin-right: 2mm;
}

.language-section {
  padding: 1mm 0;
}

.language-section .left-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.language-section .left-section p {
  font-size: 13px !important;
}

.language-section .left-section .bar {
  display: flex;
  gap: 1mm;
  align-items: center;
}

.bar div {
  height: 10px;
  width: 10px;
  border-radius: 50%;
  background-color: #000;
}

.bar div1 {
  height: 10px;
  width: 10px;
  border-radius: 50%;
  background-color: #949494;
}

.language-section .right-section {
  padding-top: 2px;
  max-width: 100%;
}

.language-section .right-section p {
  font-size: 13px !important;
  text-align: right;
}

/* -------------------------------------------------- */

@media print {

  body,
  .page {
    margin: 0;
    box-shadow: none;
  }

  .main .top {
    border-radius: 0px;
  }

  .middle-right {
    border-radius: 0px;
  }
}
</style>
