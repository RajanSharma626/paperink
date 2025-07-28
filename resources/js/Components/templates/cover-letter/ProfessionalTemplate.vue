<template>
  <div class="page">
    <div class="main">
      <div class="top">
        <div class="left">
          <div class="line"></div>
          <div class="text-section">
            <div class="name-section">
              <div v-if="cover.name" class="name">
                {{ cover.name }}
              </div>
              <div v-if="cover.jobTitle" class="profession">
                {{ cover.jobTitle }}
              </div>
            </div>
            <div class="heading">
              <span
                v-if="cover.company || cover.co_street || cover.co_city || cover.co_postal || cover.co_country || cover.date"
                class="to"
                >To:</span
              >
              <h6 v-if="cover.company">{{ cover.company }}</h6>
              <h6
                v-if="cover.co_street || cover.co_city || cover.co_postal || cover.co_country"
              >
                {{ [cover.co_street, cover.co_city, cover.co_postal].filter(Boolean).join(' ') }}<br />
                {{ cover.co_country }}
              </h6>
            </div>
          </div>
        </div>
        <div class="right">
          <p v-if="cover.email" class="details">
            <i class="icons bi-envelope"></i>{{ cover.email }}
          </p>
          <p v-if="cover.phone" class="details">
            <i class="icons bi-phone"></i>{{ cover.phone }}
          </p>
          <p v-if="cover.address" class="details">
            <i class="icons bi-geo-alt"></i>{{ cover.address }}
          </p>
          <p v-if="cover.linkedin" class="details">
            <i class="icons bi-linkedin"></i>{{ cover.linkedin }}
          </p>
          <p v-if="cover.web" class="details">
            <i class="icons bi-browser-chrome"></i>{{ cover.web }}
          </p>
        </div>
      </div>
      <div class="middle">
        <div class="line-1"></div>
        <div class="subject">
          <p v-if="cover.mr || cover.hiringManager">
            Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},
          </p>
          <br />
          <p v-if="cover.letter" v-html="cover.letter"></p>
        </div>
      </div>
      <div class="last" v-if="cover.name">
        <div class="line2"></div>
        <div class="last-section">
          <p>Sincerly</p>
          <p>{{ cover.name }}</p>
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
@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

:root {
  --firstcolor: #5d355e;
  --secondcolor: #BF965C;
}

* {
  margin: 0;
  padding: 0;
  font-family: 'Roboto', sans-serif;
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
  padding: 2mm 0;
}

.main .top {
  display: flex;
  justify-content: space-between;
  padding: 1mm 2mm;
}

.top .left {
  display: flex;
  width: 65%;
}

.left .line {
  width: 7px;
  background-color: #5d355e;
  height: 100%;
  border-radius: 10px;
}

.text-section {
  margin: 0 2mm;
  background-color: #ebebeb;
  border-radius: 5px 0 0 5px;
  width: 100%;
}

.name-section {
  padding: 4mm;
}

.name-section .name {
  font-size: 30px;
  color: #5d355e;
}

.name-section .profession {
  font-size: 18px;
  color: #BF965C;
}

.left .heading {
  padding: 2mm 2mm 8mm 4mm;
  line-height: 1.2;
}

.heading .to {
  font-size: 14px !important;
  color: #BF965C;
  font-style: italic;
}

.heading h6 {
  font-size: 17px !important;
  font-weight: 500;
}

.right {
  background-color: #5d355e;
  color: #fff;
  width: 35%;
  line-height: 2;
  padding: 2mm 6mm;
  border-radius: 0 5px 5px 0;
}

.right .details {
  font-size: 12px !important;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 5px;
  text-wrap: none;
  overflow: hidden;
  font-weight: 400;
  padding-block: 1mm;
}

.details .icons {
  padding: 1mm 2mm;
  font-size: 10px !important;
  color: #BF965C;
  text-align: center;
  border-radius: 15px;
  background-color: #5d355e;
  border: 1px solid #BF965C;
}

.middle {
  padding: 2mm 2mm;
  display: flex;
}

.line-1 {
  width: 24px;
  background-color: #BF965C;
  border-radius: 10px;
}

.subject {
  margin: 0 2mm;
  padding: 4mm;
}

.subject p {
  font-size: 14px !important;
  font-weight: 500;
  line-height: 1.3;
}

.subject ul {
  font-size: 14px !important;
  line-height: 1.3;
  padding-left: 4mm;
  font-weight: 500;
}

.subject ul li::marker {
  color: #BF965C;
  font-size: 16px !important;
}

.last {
  padding: 1mm 2mm;
  display: flex;
}

.line2 {
  width: 7px;
  background-color: #5d355e;
  border-radius: 10px;
}

.last-section {
  margin: 0 2mm;
  padding: 2mm 4mm;
}

.last-section p {
  font-size: 14px !important;
  font-weight: 500;
  line-height: 1.3;
}

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
