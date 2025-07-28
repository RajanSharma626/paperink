<template>
  <div class="page">
    <div class="main">
      <div class="top">
        <div class="left">
          <div class="name-section">
            <div v-if="cover.name" class="name">
              {{ cover.name }}
            </div>
            <div v-if="cover.jobTitle" class="profession">
              {{ cover.jobTitle }}
            </div>
          </div>
          <template v-if="cover.date">
            <br>
            <span class="date">{{ cover.date }}</span>
          </template>
        </div>
        <div class="right">
          <p v-if="cover.email" class="details">{{ cover.email }}<i class="icons bi-envelope"></i></p>
          <p v-if="cover.phone" class="details">{{ cover.phone }}<i class="icons bi-phone"></i></p>
          <p v-if="address" class="details">{{ address }}<i class="icons bi-geo-alt"></i></p>
          <p v-if="cover.linkedin" class="details">{{ cover.linkedin }}<i class="icons bi-linkedin"></i></p>
          <p v-if="cover.web" class="details">{{ cover.web }}<i class="icons bi-browser-chrome"></i></p>
        </div>
      </div>
      <div class="middle">
        <div class="subject">
          <template v-if="cover.mr || cover.hiringManager">
            <p>Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},</p>
          </template>
          <br><br>
          <span v-if="cover.letter" v-html="cover.letter"></span>
          <template v-if="cover.name">
            <br>
            <br>
            <p>Sincerly</p>
            <p>{{ cover.name }}</p>
          </template>
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

// Compose address string for display
const address = [
  props.cover.street,
  props.cover.city,
  props.cover.postal,
  props.cover.country
].filter(Boolean).join(' ');
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Merriweather&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

:root {
  --firstcolor: #c9483a;
  --secondcolor: #7c6766;
}

* {
  margin: 0;
  padding: 0;
  font-family: 'Merriweather', serif;
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
  padding: 8mm 12mm 8mm 0mm;
}

.left .name-section {
  padding: 2mm 2mm 2mm 12mm;
  line-height: 1.3;
  background-color: #c9483a;
  color: #fff;
}

.name-section .name {
  font-size: 30px;
}

.name-section .profession {
  font-size: 18px;
}

.top .date {
  font-size: 14px;
  font-style: italic;
  padding-left: 12mm;
  color: #7c6766;
}

.top .right {
  line-height: 2;
}

.top .right .details {
  font-size: 12px !important;
  font-weight: 500;
  text-align: right;
}

.top .right .details .icons {
  padding-left: 8px;
  font-size: 12px;
  color: #c9483a;
}

.middle {
  padding: 1mm 12mm;
}

.subject p {
  font-size: 14px !important;
  font-weight: 500;
}

.subject ul {
  font-size: 14px !important;
  padding-left: 4mm;
  font-weight: 500;
  list-style-type: circle;
}

.subject ul li {
  padding: 4px 0;
}

.subject ul li::marker {
  color: #c9483a;
  font-size: 15px !important;
}

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
