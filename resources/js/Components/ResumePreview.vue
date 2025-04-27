<template>
    <div v-html="compiledTemplate"></div>
</template>

<script>
export default {
    props: ['data'],
    computed: {
        compiledTemplate() {
            const tpl = `
        <div class="page" id="page" style="font-family: 'Ubuntu', sans-serif; width: 210mm; padding: 10mm; background: white;">
          <div class="top">
            <div class="name" style="font-size: 30px; color: #455aa3;">${this.data.name}</div>
            <div class="profession" style="font-size: 18px;">${this.data.title}</div>
            <div class="bio" style="font-size: 14px;">${this.data.summary}</div>
          </div>
          <hr />
          <div class="middle" style="margin-top: 10px;">
            ${this.data.email ? `<p><strong>Email:</strong> ${this.data.email}</p>` : ''}
            ${this.data.phone ? `<p><strong>Phone:</strong> ${this.data.phone}</p>` : ''}
            ${this.data.address ? `<p><strong>Address:</strong> ${this.data.address}</p>` : ''}
            ${this.data.linkedin ? `<p><strong>LinkedIn:</strong> ${this.data.linkedin}</p>` : ''}
            ${this.data.web ? `<p><strong>Website:</strong> ${this.data.web}</p>` : ''}
          </div>
          <hr />
          <div class="skills">
            <h4>Skills</h4>
            ${this.data.skills.map(skill => `<span style="background:#455aa3;color:white;padding:3px 6px;border-radius:4px;margin-right:5px;">${skill.skill}</span>`).join('')}
          </div>
          <br />
          <div class="work-experience">
            <h4>Work Experience</h4>
            ${this.data.experiences.map(exp => `
              <h5>${exp.role} @ ${exp.company}</h5>
              <small><em>${exp.startYear} - ${exp.endYear} | ${exp.address}</em></small>
              <p>${exp.description}</p>
            `).join('')}
          </div>
          <br />
          <div class="education">
            <h4>Education</h4>
            ${this.data.education.map(edu => `
              <h5>${edu.degree}</h5>
              <p>${edu.university} (${edu.startYear} - ${edu.endYear})</p>
            `).join('')}
          </div>
          <br />
          <div class="organization">
            <h4>Organizations</h4>
            ${this.data.orgs.map(org => `<p>${org.org} (${org.start} - ${org.end})</p>`).join('')}
          </div>
          <br />
          <div class="languages">
            <h4>Languages</h4>
            ${this.data.lang.map(l => `<p>${l.lang} - <em>${l.lvl}</em></p>`).join('')}
          </div>
        </div>
        `;
            return tpl;
        }
    }
}
</script>
