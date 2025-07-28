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
            <br v-if="cover.date">
            <span v-if="cover.date" class="date">{{ cover.date }}</span>
          </div>
        </div>
        <div class="right">
          <p v-if="cover.email" class="details">{{ cover.email }}<i class="icons bi-envelope"></i></p>
          <p v-if="cover.phone" class="details">{{ cover.phone }}<i class="icons bi-phone"></i></p>
          <p v-if="cover.address" class="details">{{ cover.address }}<i class="icons bi-geo-alt"></i></p>
          <p v-if="cover.linkedin" class="details">{{ cover.linkedin }}<i class="icons bi-linkedin"></i></p>
          <p v-if="cover.web" class="details">{{ cover.web }}<i class="icons bi-browser-chrome"></i></p>
        </div>
      </div>
      <div class="middle">
        <div class="subject">
          <p v-if="cover.hiringManager || cover.mr">Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},</p>
          <br>
          <div v-if="cover.letter" v-html="cover.letter"></div>
          <div v-if="cover.name">
            <br>
            <p>Sincerly</p>
            <p>{{ cover.name }}</p>
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
:root{
  --firstcolor:#ce293d;
  --secondcolor:#7c6766;
}
*{
  margin: 0;
  padding: 0;
  font-family: 'Noto Sans', sans-serif;
}
.page{
  background: #fff;
  width: 210mm;
  min-height: 297mm;
  margin: 10mm auto;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}
.main .top{
  display: flex;
  justify-content: space-between;
  padding:8mm 12mm 8mm 12mm ;
  background-color: #f1f1f1;
  border-radius: 10px 10px 0 0 ;
}
.top .name-section{
  line-height: 1.3;
}
.name-section .name{
  font-size: 30px !important;
  color: #ce293d;
}
.name-section .profession{
  font-size: 18px !important;
  color: #7c6766;
}
.top .date{
  font-size: 14px !important;
  font-style: italic;
  color: #7c6766;
}
.top .right{
  line-height: 2;
}
.right .details{
  font-size: 12px !important;
  font-weight: 500;
  text-align: right;
}
.details .icons{
  padding-left: 8px;
  font-size: 12px !important;
  color: #ce293d;
}
.page .middle{
  padding: 2mm 12mm ;
}
.middle .subject{
  padding-top: 3mm;
}
.subject p{
  font-size: 15px !important;
  font-weight: 500;
  line-height: 1.5;
}
.subject ul{
  font-size: 15px !important;
  line-height: 1.5;
  font-weight: 500;
  list-style-type: square;
  padding-left: 4mm;
}
.subject ul li::marker{
  font-size: 12px !important;
  color: #7c6766;
}

@media print {
  body, .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
