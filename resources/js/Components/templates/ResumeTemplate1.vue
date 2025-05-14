<template>
    <div class="page border" id="page">
      <div class="main">
        <div class="top">
          <div v-if="resume.name" class="name">{{ resume.name + ' ' + resume.lastName }}</div>
          <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
          <div v-if="resume.summary" class="bio" v-html="resume.summary"></div>
        </div>

        <div v-if="hasContactDetails" class="middle">
          <div class="details-section">
            <p v-if="resume.email" class="details"><i class="icons bi-envelope-fill"></i> {{ resume.email }}</p>
            <p v-if="resume.phone" class="details"><i class="icons bi-phone-fill"></i> {{ resume.phone }}</p>
            <p v-if="resume.address" class="details"><i class="icons bi-geo-alt-fill"></i>
              {{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}
            </p>
            <p v-if="resume.linkedin" class="details"><i class="icons bi-linkedin"></i> {{ resume.linkedin }}</p>
            <p v-if="resume.web" class="details"><i class="icons bi bi-skype"></i> {{ resume.web }}</p>
          </div>
        </div>

        <div class="end">
          <div v-if="resume.skills && resume.skills.length" class="skills">
            <h4>Skills</h4>
            <div class="skills-section">
              <p>
                <span v-for="(skill, index) in resume.skills" :key="index">{{ skill.skill }}</span>
              </p>
            </div>
          </div>

          <br v-if="resume.employmentHistory && resume.employmentHistory.length" />
          <div v-if="resume.employmentHistory && resume.employmentHistory.length" class="work-experience">
            <h4>Work Experience</h4>
            <div v-for="(exp, index) in resume.employmentHistory" :key="index">
              <h5>{{ exp.jobTitle }}</h5>
              <h6>{{ exp.company }}</h6>
              <span class="date">
                <span>{{ exp.startDate }} - {{ exp.endDate }}</span>
                <span>{{ exp.city }}</span>
              </span>
              <p v-html="exp.description"></p> <!-- ✅ Renders HTML description -->
            </div>
          </div>

          <div v-if="resume.education && resume.education.length" class="education">
            <h4>Education</h4>
            <div class="education-section">
              <div v-for="(edu, index) in resume.education" :key="index">
                <h5>{{ edu.degree }}</h5>
                <h6>{{ edu.school }}</h6>
                <span class="date">{{ edu.startDate }} - {{ edu.endDate }}</span>
                <p v-html="edu.description"></p> <!-- ✅ Renders HTML description -->
                
              </div>
            </div>
          </div>

          <br v-if="resume.orgs && resume.orgs.length" />
          <div v-if="resume.orgs && resume.orgs.length" class="organization">
            <h4>Organizations</h4>
            <div class="organization-section">
              <div class="left">
                <div v-for="(org, index) in leftOrgs" :key="'left-' + index">
                  <p>{{ org.org }}<br />({{ org.start }} - {{ org.end }})</p>
                </div>
              </div>
              <div class="right">
                <div v-for="(org, index) in rightOrgs" :key="'right-' + index">
                  <p>{{ org.org }}<br />({{ org.start }} - {{ org.end }})</p>
                </div>
              </div>
            </div>
          </div>

          <br v-if="resume.lang && resume.lang.length" />
          <div v-if="resume.lang && resume.lang.length" class="languages">
            <h4>Languages</h4>
            <div class="languages-section">
              <p v-for="(language, index) in resume.lang" :key="index">
                {{ language.lang }} <br /><span>{{ language.lvl }}</span>
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
    resume: {
      type: Object,
      required: true,
    },
  });

  const hasContactDetails = computed(() => {
    const r = props.resume;
    return r.email || r.phone || r.address || r.linkedin || r.web;
  });

  const leftOrgs = computed(() => props.resume.orgs?.filter((_, i) => i % 2 === 0) || []);
  const rightOrgs = computed(() => props.resume.orgs?.filter((_, i) => i % 2 !== 0) || []);
  </script>


<style scoped>
* {
    margin: 0;
    padding: 0;
    font-family: 'Ubuntu', sans-serif;

}

.page {
    background: #fff;
    width: 210mm;
    min-height: 297mm;
    border-radius: 10px;
}

.main {
    padding: 2mm 0;
}

.top {
    padding: 5mm 10mm;
    line-height: 1.2;
}

.top .name {
    font-size: 30px;
    color: #455aa3;
}

.top .profession {
    font-size: 18px;
}

.top .bio {
    font-size: 14px;
}

.middle {
    padding: 1mm 10mm;
    border-top: 2px solid #000;
    border-bottom: 2px solid #000;
}

.middle .details-section {
    display: flex;
    align-items: center;
    gap: 4mm;
    padding: 2mm 0;
}

.details-section .details {
    font-size: 12px !important;
    font-weight: 400;
}

.details .icons {
    padding-right: 8px;
    font-size: 12px !important;
    color: #455aa3;
}

.end {
    padding: 2mm 10mm;
}

.skills {
    line-height: 1.2;
}

.skills h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #455aa3;
    margin: 5px 0;
}

.skills-section p {
    font-size: 14px;
    line-height: 2;
}

.skills-section span {
    background-color: #455aa3;
    border-radius: 4px;
    padding: 3px 6px;
    text-align: center;
    margin: 0 5px 0 0;
    color: #fff;
}

.work-experience {
    line-height: 1.2;
}

.work-experience h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #455aa3;
    margin: 5px 0;
}

.work-experience h5 {
    font-size: 16px;
}

.work-experience h6 {
    font-size: 16px;
    font-weight: 500;
}

.work-experience .date {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    font-style: italic;
    color: #455aa3;
    padding: 4px 0;
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
    color: #455aa3;
}

.education {
    line-height: 1.2;
}

.education h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #455aa3;
    margin: 5px 0;
}

.education-section {
    padding-bottom: 5px;
}

.education h5 {
    font-size: 16px;
}

.education h6 {
    font-size: 16px;
    font-weight: 500;
}

.education .date {
    font-size: 12px;
    font-style: italic;
    color: #455aa3;
    padding: 3px 0;
}

.organization {
    line-height: 1.2;

}

.organization h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #455aa3;
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

.organization-section .right p {
    padding-bottom: 4mm;
}

.languages {
    line-height: 1.2;
}

.languages h4 {
    font-size: 18px;
    text-transform: uppercase;
    color: #455aa3;
    margin: 5px 0;
}

.languages-section {
    display: flex;
    gap: 35px;
}

.languages-section p {
    font-size: 14px;
}

.languages-section p span {
    font-size: 12px;
    font-style: italic;
    color: #455aa3;
    padding: 3px 0;
}

.row {
    padding: 4px;
}

/* ------------------------------------------------------------------------------------ */

@media print {

    body,
    .page {
        margin: 0;
        box-shadow: none;
    }
}

</style>
