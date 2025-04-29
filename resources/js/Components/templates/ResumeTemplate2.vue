<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div v-if="resume.profile" class="left">
          <img :src="resume.profile" alt="Profile" />
        </div>
        <div class="right">
          <div v-if="resume.name" class="name">{{ resume.name + ' ' + resume.lastName }}</div>
          <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
          <div v-if="resume.summary" class="bio" v-html="resume.summary"></div>
        </div>
      </div>

      <div v-if="hasContactDetails" class="middle">
        <div class="details-section">
          <p v-if="resume.email" class="details">
            <i class="icons bi-envelope-fill"></i>{{ resume.email }}
          </p>
          <p v-if="resume.phone" class="details">
            <i class="icons bi-phone-fill"></i>{{ resume.phone }}
          </p>
          <p v-if="resume.address" class="details">
            <i class="icons bi-geo-alt-fill"></i>{{ [resume.address, resume.city, resume.postalCode,
            resume.country].filter(Boolean).join(', ') }}
          </p>
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

        <br v-if="resume.employmentHistory?.length" />
        <div v-if="resume.employmentHistory?.length" class="work-experience">
          <h4>Work Experience</h4>
          <div v-for="(exp, i) in resume.employmentHistory" :key="i">
            <h5>{{ exp.jobTitle }}</h5>
            <h6>{{ exp.company }}</h6>
            <span class="date">
              <span>{{ exp.startDate }} - {{ exp.endDate }}</span>
              <span>{{ exp.city }}</span>
            </span>
            <p v-html="exp.description"></p>
          </div>
        </div>

        <div v-if="resume.education?.length" class="education">
          <h4>Education</h4>
          <div v-for="(edu, i) in resume.education" :key="i">
            <h5>{{ edu.degree }}</h5>
            <h6>{{ edu.school }}</h6>
            <span class="date">
              <span>
                {{ edu.startDate }} - {{ edu.endDate }}
              </span>
              <span>{{ edu.city }}</span>
            </span>
            <p v-html="edu.description"></p> 
          </div>
        </div>

        <br v-if="resume.orgs?.length" />
        <div v-if="resume.orgs?.length" class="organization">
          <h4>Organizations</h4>
          <div class="organization-section">
            <div class="left">
              <p v-for="(org, i) in leftOrgs" :key="'left-' + i">
                {{ org.org }}<br />({{ org.start }} - {{ org.end }})
              </p>
            </div>
            <div class="right">
              <p v-for="(org, i) in rightOrgs" :key="'right-' + i">
                {{ org.org }}<br />({{ org.start }} - {{ org.end }})
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
})

const hasContactDetails = computed(() => {
  return props.resume.email || props.resume.phone || props.resume.address
})

const leftOrgs = computed(() => props.resume.orgs?.filter((_, i) => i % 2 === 0) || [])
const rightOrgs = computed(() => props.resume.orgs?.filter((_, i) => i % 2 !== 0) || [])
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');


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

.main .top {
  display: flex;
  gap: 20px;
  color: #000;
  border-radius: 10px 10px 0 0;
  padding: 5mm 10mm;
}

.top .left img {
  height: 160px;
  width: 160px;
  border-radius: 10px;
}

.top .right {
  line-height: 1.2;
}

.right .name {
  font-size: 30px;
}

.right .profession {
  font-size: 18px;
  color: #666766;
}

.right .bio {
  font-size: 14px;
  padding: 1mm 0;
}

.middle {
  padding: 1mm 10mm;
  background-color: #f1f1f1;
}

.details-section {
  display: flex;
  align-items: center;
  gap: 4mm;
  padding: 2mm 0;
}

.details {
  font-size: 12px !important;
  font-weight: 400;
}

.details .icons {
  padding-right: 8px;
  font-size: 12px !important;
  color: #37a569;
}

.end {
  padding: 2mm 10mm;
}

.skills h4,
.work-experience h4,
.education h4,
.organization h4 {
  font-size: 18px;
  text-transform: uppercase;
  color: #37a569;
  margin: 5px 0;
}

.skills-section span {
  background-color: #37a569;
  border-radius: 4px;
  padding: 3px 6px;
  text-align: center;
  margin: 0 5px 0 0;
  color: #fff;
}

.work-experience h5,
.education h5 {
  font-size: 16px;
}

.work-experience h6,
.education h6 {
  font-size: 16px;
  font-weight: 500;
}

.date {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  font-style: italic;
  color: #37a569;
}

.work-experience p,
.education {
  font-size: 14px;
  padding-bottom: 4mm;
}

.organization-section {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
}

.organization-section .left p,
.organization-section .right p {
  padding-bottom: 4mm;
}

@media print {

  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
