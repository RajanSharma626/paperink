<template>
  <div class="page">
    <div class="main">
      <div class="name-section">
        <div v-if="cover.name" class="name">
          {{ cover.name }}
        </div>
        <div v-if="cover.jobTitle" class="profession">
          {{ cover.jobTitle }}
        </div>
      </div>
      <div v-if="cover.email || cover.phone || cover.address || cover.linkedin" class="details-section">
        <div class="left">
          <p v-if="cover.email" class="details">
            <i class="icons bi-envelope"></i> {{ cover.email }}
          </p>
          <p v-if="cover.phone" class="details">
            <i class="icons bi-phone"></i> {{ cover.phone }}
          </p>
        </div>
        <div class="right">
          <p v-if="cover.address" class="details">
            <i class="icons bi-geo-alt"></i> {{ cover.address }}
          </p>
          <p v-if="cover.linkedin" class="details">
            <i class="icons bi-linkedin"></i> {{ cover.linkedin }}
          </p>
        </div>
      </div>

      <div v-if="
        cover.company ||
        cover.co_street ||
        cover.co_city ||
        cover.co_postal ||
        cover.co_country ||
        cover.date
      " class="heading-section">
        <div class="heading">
          <span class="to">To:</span>
          <h6 v-if="cover.company">{{ cover.company }}</h6>
          <h6 v-if="
            cover.co_street ||
            cover.co_city ||
            cover.co_postal ||
            cover.co_country
          ">
            {{ [cover.co_street, cover.co_city, cover.co_postal].filter(Boolean).join(' ') }}<br />
            {{ cover.co_country }}
          </h6>
        </div>
      </div>

      <div class="line-section">
        <div class="line"></div>
      </div>

      <div class="subject">
        <p v-if="cover.mr || cover.hiringManager">
          Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},
        </p>
        <br>
        <span v-if="cover.letter" v-html="cover.letter"></span>
        
      </div>

      <div class="line-section">
        <div class="line"></div>
      </div>

      <div v-if="cover.name" class="last">
        <p>Sincerly</p>
        <p>{{ cover.name }}</p>
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
@import url('https://fonts.googleapis.com/css2?family=Hind&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

:root {
  --firstcolor: #313c4e;
  --secondcolor: #449399;
}

* {
  margin: 0;
  padding: 0;
  font-family: 'Hind', sans-serif;
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

.name-section {
  padding: 5mm 12mm 5mm 12mm;
}

.name-section .name {
  font-size: 35px !important;
  padding-bottom: 0;
  line-height: 1;
  color: #313c4e;
}

.name-section .profession {
  font-size: 20px !important;
  color: #449399;
}

.details-section {
  height: 75px;
  margin: 0 6mm;
  padding: 2mm 6mm;
  background-color: #449399;
  display: flex;
  align-items: center;
  gap: 200px;
  border-radius: 6px;
  line-height: 1.8;
}

.details-section .details {
  font-size: 12px !important;
  color: #fff;
  font-weight: 400;
}

.details .icons {
  padding-right: 8px;
  font-size: 12px !important;
}

.heading-section {
  padding: 2mm 12mm;
}

.heading-section .heading {
  line-height: 1.3;
}

.heading .to {
  font-size: 14px !important;
  color: #449399;
  font-style: italic;
}

.heading h6 {
  font-size: 17px !important;
  font-weight: 600;
}

.line-section {
  padding: 3mm 12mm;
}

.line-section .line {
  height: 4px;
  width: 50%;
  background: linear-gradient(to right, #449399, #fff);
}

.subject {
  padding: 2mm 12mm;
}

.subject p {
  font-size: 15px !important;
  font-weight: 500;
  line-height: 1.5;
}

.subject p span {
  font-weight: 600;
}

.subject ul {
  font-size: 15px !important;
  line-height: 1.5;
  padding-left: 4mm;
  font-weight: 500;
}

.subject ul li::marker {
  color: #449399;
  font-size: 15px;
}

.last {
  padding: 1mm 12mm;
}

.last p {
  font-size: 15px !important;
  font-weight: 500;
  line-height: 1.5;
}

@media print {

  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
