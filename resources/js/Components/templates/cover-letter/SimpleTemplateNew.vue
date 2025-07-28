<template>
  <div class="page">
    <div class="main">
      <div class="top">
        <div v-if="cover.name" class="name">
          {{ cover.name }}
        </div>
        <div v-if="cover.jobTitle" class="profession">
          {{ cover.jobTitle }}
        </div>
        <div class="details-section">
          <p v-if="cover.email" class="details"><i class="icons bi-envelope"></i>{{ cover.email }}</p>
          <p v-if="cover.phone" class="details"><i class="icons bi-phone"></i>{{ cover.phone }}</p>
          <p v-if="address" class="details"><i class="icons bi-geo-alt"></i>{{ address }}</p>
          <p v-if="cover.linkedin" class="details"><i class="icons bi-linkedin"></i>{{ cover.linkedin }}</p>
          <p v-if="cover.web" class="details"><i class="icons bi-globe"></i>{{ cover.web }}</p>
        </div>
      </div>
      <div class="middle">
        <div class="heading">
          <template v-if="cover.company || cover.co_street || cover.co_city || cover.co_postal || cover.co_country || cover.date">
            <span class="to">To:</span>
            <template v-if="cover.company">
              <h6>{{ cover.company }}</h6>
            </template>
            <template v-if="cover.co_street || cover.co_city || cover.co_postal || cover.co_country">
              <h6>
                {{ [cover.co_street, cover.co_city, cover.co_postal].filter(Boolean).join(' ') }}<br>
                {{ cover.co_country }}
              </h6>
            </template>
            <template v-if="cover.date">
              <div class="date">{{ cover.date }}</div>
            </template>
          </template>
        </div>
      </div>
      <div class="border-line">
        <div class="squar"></div>
        <div class="line"></div>
      </div>
      <div class="end">
        <div class="subject">
          <template v-if="cover.mr || cover.hiringManager">
            <p>Dear {{ [cover.mr, cover.hiringManager].filter(Boolean).join(' ') }},</p>
          </template>
          <br>
          <span v-if="cover.letter" v-html="cover.letter"></span>
        </div>
      </div>
      <div class="border-line">
        <div class="squar"></div>
        <div class="line"></div>
      </div>
      <template v-if="cover.name">
        <div class="last">
          <p>Sincerely</p>
          <p>{{ cover.name }}</p>
        </div>
      </template>
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
@import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

:root {
  --firstcolor: #505865;
  --secondcolor: #89bbbf;
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

.main .top {
  padding: 8mm 12mm 8mm 12mm;
  text-align: center;
}

.top .name {
  font-size: 35px !important;
  color: #505865;
}

.top .profession {
  font-size: 18px !important;
  color: #89bbbf;
  font-weight: 500;
  padding-top: 2px;
}

.top .details-section {
  display: flex;
  gap: 30px;
  align-items: center;
  justify-content: center;
  margin-top: 10mm;
}

.details-section .details {
  font-size: 13px !important;
  font-weight: 500;
  text-align: right;
  color: #212121;
}

.details-section .details .icons {
  padding-right: 5px;
  font-size: 13px !important;
  color: #89bbbf;
}

.main .middle {
  padding: 4mm 12mm;
}

.middle .heading {
  line-height: 1.3;
}

.heading .to {
  font-size: 13px !important;
  color: #89bbbf;
  font-style: italic;
}

.heading h6 {
  font-size: 17px !important;
  font-weight: 500;
}

.heading .date {
  font-size: 16px !important;
  color: #9e9e9e;
  font-style: italic;
  margin-top: 2px;
}

.border-line {
  padding: 15px;
}

.border-line .squar {
  margin-left: 15%;
  margin-right: 15%;
  width: 50%;
  height: 5px;
  position: relative;
}

.squar::after,
.squar::before {
  content: '';
  position: absolute;
  margin: auto;
  height: 8px;
  background: #505865;
  width: 2%;
  top: 45%;
  transform: rotate(45deg);
  border-radius: 3px;
}

.squar::after {
  left: 25%;
}

.squar::before {
  right: -16%;
}

.border-line .line {
  height: 2px;
  width: 45%;
  background: #505865;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  margin: auto;
}

.main .end {
  padding: 4mm 12mm;
}

.end .subject {
  margin-top: 5px;
}

.subject p {
  font-size: 15px;
  font-weight: 500;
}

.subject ul {
  font-size: 15px;
  line-height: 1.5;
  padding-left: 4mm;
  font-weight: 500;
  list-style-type: circle;
}

.subject ul li::marker {
  color: #89bbbf;
  font-size: 18px !important;
}

.last {
  padding: 4mm 12mm;
}

.last p {
  font-size: 14px !important;
  font-weight: 500;
}

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
