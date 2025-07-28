<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div class="left">
          <div v-if="resume.name" class="name fw-semibold">{{ resume.name }} <span v-if="resume.lastName"> {{
          resume.lastName }}</span></div>
          <div v-if="resume.jobTitle" class="profession">{{ resume.jobTitle }}</div>
          <div v-if="resume.summary" class="bio" v-html="resume.summary"></div>
        </div>
        <div class="right">
          <div
            v-if="resume.phone || resume.email || resume.address || resume.city || resume.country || resume.web"
            class="details-section"
          >
            <p v-if="resume.email" class="details">
              {{ resume.email }}
              <i class="icons bi-envelope-fill"></i>
            </p>
            <p v-if="resume.phone" class="details">
              {{ resume.phone }}
              <i class="icons bi-phone-fill"></i>
            </p>
            <p v-if="resume.address || resume.city || resume.country" class="details">
              {{ [resume.address, resume.city, resume.postalCode, resume.country].filter(Boolean).join(', ') }}
              <i class="icons bi-geo-alt-fill"></i>
            </p>
            <!--
            <p class="details">linkedin.com/in/jon.snow<i class="icons bi-linkedin"></i></p>
            <p class="details">jon.snow<i class="icons bi bi-skype"></i></p>
            -->
          </div>
        </div>
      </div>

      <div class="end">
        <div v-if="resume.skills?.length" class="skills">
          <h4>Skills</h4>
          <div class="skills-section">
            <p>
              <span
                v-for="(skill, i) in resume.skills"
                :key="'skill-' + i"
              >{{ skill.skill || skill.skill_name }}</span>
            </p>
          </div>
        </div>

        <br v-if="resume.skills?.length && resume.employmentHistory?.length">

        <div v-if="resume.employmentHistory?.length" class="work-experience">
          <h4>work experience</h4>
          <div
            v-for="(exp, i) in resume.employmentHistory"
            :key="'exp-' + i"
          >
            <h5>{{ exp.jobTitle || exp.role }}</h5>
            <h6>{{ exp.company }}</h6>
            <span class="date">
              <span>
                {{ exp.startYear || exp.startDate }} - {{ exp.endYear || exp.endDate || "Present" }}
              </span>
              <span>{{ exp.address || exp.city }}</span>
            </span>
            <p v-if="exp.description" v-html="exp.description"></p>
          </div>
        </div>

        <br v-if="(resume.employmentHistory?.length && resume.education?.length)">

        <div v-if="resume.education?.length" class="education">
          <h4>Education</h4>
          <div
            v-for="(edu, i) in resume.education"
            :key="'edu-' + i"
          >
            <h5>{{ edu.degree }}</h5>
            <h6>{{ edu.school || edu.university }}</h6>
            <span class="date">
              {{ edu.startYear || edu.startDate }} - {{ edu.endYear || edu.endDate || "Present" }}
            </span>
            <br>
          </div>
        </div>

        <!--
        <br>
        <div class="organization">
          <h4>Organizations</h4>
          <div class="organization-section">
            <div class="left">
              <p>American Management Association
                <br>(2015 - Present)</p>
              <p>eBusiness Association (eBA) (2013 - Present)</p>
            </div>
            <div class="right">
              <p>Association Of Private Enterprise Education <br>(2014 - Present)</p>
            </div>
          </div>
        </div>
        -->
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
:root{
  --maincolor:#313c4e;
}
*{
  margin: 0;
  padding: 0;
  font-family: 'Ubuntu', sans-serif;
}
.page{
  background: #fff;
  width: 210mm;
  min-height: 297mm;
  margin: 10mm auto;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}
.main{
  padding: 2mm 10mm;
}
.top{
  padding: 3mm 0;
  line-height: 1.2;
  display: flex;
  justify-content: space-between;
}
.top .left{
  width: 60%;
}
.left .name{
  font-size: 30px;
  color: #313c4e;
}
.left .profession{
  font-size: 18px;
  color: #666770;
}
.left .bio{
  font-size: 14px;
  padding: 2mm 0;
}
.top .right{
  width: 40%;
  text-align: right;
  line-height: 2;
}
.details-section .details{
  font-size: 11px !important;
  font-weight: 400;
}
.details .icons{
  padding-left: 8px;
  font-size: 12px !important;
  color: #313c4e;
}
.end{
  padding: 2mm 0mm;
}
.skills{
  line-height: 1.2;
}
.skills h4{
  font-size: 18px;
  text-align: center;
  text-transform: uppercase;
  color: #313c4e;
  border-top: 2px solid #000;
  border-bottom: 2px solid #000;
  padding: 1mm 0;
  margin: 5px 0;
}
.skills-section p{
  font-size: 14px;
  line-height: 2;
  padding-top: 2mm;
}
.skills-section span{
  background-color: #313c4e;
  border-radius: 4px;
  padding: 0px 6px;
  text-align: center;
  margin: 3px;
  color: #fff;
  display: inline-block;
}
.work-experience{
  line-height: 1.2;
}
.work-experience h4{
  font-size: 18px;
  text-align: center;
  text-transform: uppercase;
  color: #313c4e;
  border-top: 2px solid #000;
  border-bottom: 2px solid #000;
  padding: 1mm 0;
  margin: 5px 0;
}
.work-experience h5{
  font-size: 16px;
  padding-top: 2mm;
}
.work-experience h6{
  font-size: 16px;
  font-weight: 500;
}
.work-experience .date{
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  font-style: italic;
  color: #313c4e;
  padding: 4px 0;
}
.work-experience ul{
  font-size: 14px !important;
  padding-left: 4mm;
  line-height: 1.3;
}
.work-experience p{
  font-size: 14px;
  padding-bottom: 2mm;
}
.work-experience li::marker{
  color: #313c4e;
}
.education{
  line-height: 1.2;
  margin-bottom: 5mm;
}
.education h4{
  font-size: 18px;
  text-align: center;
  text-transform: uppercase;
  color: #313c4e;
  border-top: 2px solid #000;
  border-bottom: 2px solid #000;
  padding: 1mm 0;
  margin: 5px 0;
}
.education h5{
  padding-top: 2mm;
  font-size: 16px;
}
.education h6{
  font-size: 16px;
  font-weight: 500;
}
.education .date{
  font-size: 12px;
  font-style: italic;
  color: #313c4e;
  padding: 3px 0;
}
.organization{
  line-height: 1.2;
}
.organization h4{
  font-size: 18px;
  text-align: center;
  text-transform: uppercase;
  color: #313c4e;
  border-top: 2px solid #000;
  border-bottom: 2px solid #000;
  padding: 1mm 0;
  margin: 5px 0;
}
.organization-section {
  padding-top: 2mm;
  display: flex;
  justify-content: space-between;
  font-size: 14px;
}
.organization-section .left p{
  padding-bottom: 4mm;
}
@media print {
  body, .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
