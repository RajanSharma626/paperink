<template>
  <div class="page" id="page">
    <div class="main">
      <div class="top">
        <div v-if="cover.name" class="name">
          {{ cover.name }}
        </div>
        <div v-if="cover.jobTitle" class="profession">
          {{ cover.jobTitle }}
        </div>
      </div>
      <div class="middle">
        <div class="left">
          <div class="heading">
            <template
              v-if="cover.company || cover.co_street || cover.co_city || cover.co_postal || cover.co_country || cover.date">
              <template v-if="cover.date">
                <p>{{ cover.date }}</p>
              </template>
              <br>
              <template v-if="cover.company">
                <h6>{{ cover.company }}</h6>
              </template>
              <template v-if="cover.co_street || cover.co_city || cover.co_postal || cover.co_country">
                <h6>
                  {{ [cover.co_street, cover.co_city, cover.co_postal].filter(Boolean).join(' ') }}<br>
                  {{ cover.co_country }}
                </h6>
              </template>
            </template>
          </div>
          <br>
          <div class="subject">
            <template v-if="cover.mr || cover.hiringManager">
              <p>Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},</p>
            </template>
            <br>
            <span v-if="cover.letter" v-html="cover.letter"></span>
            <template v-if="cover.name">
              <br>
              <p>Sincerly</p>
              <p>{{ cover.name }}</p>
            </template>
          </div>
        </div>
        <div class="right">
          <div class="personal-info">
            <h4>Personal Info</h4>
            <template v-if="cover.phone">
              <h6>Phone</h6>
              <p>{{ cover.phone }}</p>
            </template>
            <template v-if="cover.email">
              <h6>E-mail</h6>
              <p>{{ cover.email }}</p>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  cover: {
    type: Object,
    required: true
  }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Arimo&display=swap');

* {
  margin: 0;
  padding: 0;
  font-family: 'Arimo', sans-serif;
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
  line-height: 1.3;
  min-height: 100%;
}

.top {
  background-color: rgb(29, 17, 17);
  color: #fff;
  padding: 5mm;
  border-radius: 10px 10px 0 0;
}

.name {
  font-size: 25px;
  text-transform: uppercase;
}

.profession {
  font-size: 17px;
  padding-top: 1mm;
}

.middle {
  display: flex;
  justify-content: space-between;
  gap: 6mm;
}

.left {
  width: 70%;
  padding-top: 5mm;
  line-height: 1.4;
  padding: 5mm 0 5mm 5mm;
}

.left p {
  font-size: 14px;
}

.left h6 {
  font-size: 16px;
  font-weight: normal;
}

.left ul {
  padding-left: 4mm;
  font-size: 14px
}

.right {
  width: 25%;
  height: 270mm;
  border-radius: 0 0px 10px 0;
  padding: 5mm;
  background-color: #cfcfcf;
}

.personal-info h4 {
  border-bottom: 1px solid#525252;
  font-size: 18px;
  font-weight: normal;
  margin-bottom: 1mm;
}

.personal-info {
  line-height: 2;
}

.personal-info h6 {
  font-size: 16px;
  font-weight: normal;
}

.personal-info p {
  font-size: 13px;
}

@media print {

  body,
  .page {
    margin: 0;
    box-shadow: none;
  }

  .right {
    border-radius: 0px;
  }

  .top {
  border-radius: 0px;
}
}
</style>
