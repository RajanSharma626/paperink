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
            <br>
            <div class="heading">
              <span v-if="cover.company || cover.co_street || cover.co_city || cover.co_postal || cover.co_country || cover.date" class="to">To:</span>
              <h6 v-if="cover.company">{{ cover.company }}</h6>
              <h6 v-if="cover.co_street || cover.co_city || cover.co_postal || cover.co_country">
                {{ [cover.co_street, cover.co_city, cover.co_postal].filter(Boolean).join(' ') }}<br>
                {{ cover.co_country }}
              </h6>
            </div>
          </div>
        </div>
        <div class="right">
          <p v-if="cover.email" class="details"><i class="icons bi-envelope"></i>{{ cover.email }}</p>
          <p v-if="cover.phone" class="details"><i class="icons bi-phone"></i>{{ cover.phone }}</p>
          <p v-if="cover.address" class="details"><i class="icons bi-geo-alt"></i>{{ cover.address }}</p>
          <p v-if="cover.linkedin" class="details"><i class="icons bi-linkedin"></i>{{ cover.linkedin }}</p>
        </div>
      </div>
      <div class="line-section">
        <div class="line"></div>
        <div class="line1"></div>
        <div class="line"></div>
      </div>
      <div class="middle">
        <div class="subject">
          <p v-if="cover.mr || cover.hiringManager">Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},</p>
          <br>
          <p class="coverLetter" v-if="cover.letter" v-html="cover.letter"></p>
        </div>
        <br>
      </div>
      <div class="line-section">
        <div class="line"></div>
        <div class="line1"></div>
        <div class="line"></div>
      </div>
      <div v-if="cover.name">
        <br>
        <div class="last">
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

:root{
  --firstcolor:#10514b;
  --secondcolor:#e89267;
}
*{
  margin: 0;
  padding: 0;
  font-family: 'Roboto', sans-serif;
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
  padding: 2mm 0;
}

.coverLetter p,
.coverLetter h1,
.coverLetter h2,
.coverLetter h3,
.coverLetter h4,
.coverLetter h5,
.coverLetter h6 {
  /* Remove Bootstrap font-weight, margin, and font-size */
  font-weight: unset !important;
  margin: 0 !important;
  font-size: unset !important;
  /* Remove Bootstrap heading color */
  color: unset !important;
  /* Remove Bootstrap line-height */
  line-height: unset !important;
  /* Remove Bootstrap padding */
  padding: 0 !important;
}

.main .top{
  display: flex;
  justify-content: space-between;
  padding:8mm 10mm 8mm 10mm ;
}
.left .name-section{
  line-height: 1.3;
}
.name-section .name{
  font-size: 30px !important;
  color: #10514b;
}
.name-section .profession{
  font-size: 18px !important;
  color: #e89267;
}
.left .heading{
  line-height: 1.2;
}
.heading .to{
  font-size: 14px !important;
  color: #10514b;
  font-style: italic;
}
.heading h6{
  font-size: 17px !important;
  font-weight: 500;
}
.top .right{
  background-color: #10514b;
  color: #fff;
  width: 250px;
  line-height: 2;
  padding: 4mm 6mm;
  border-radius: 10px;
}
.right .details{
  font-size: 12px !important;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 5px;
  text-wrap: none;
  overflow: hidden;
  font-weight: 400;
  padding: 2mm 0;
}
.details .icons{
  padding: 0.5mm 1.5mm;
  font-size: 12px !important;
  color: #10514b;
  text-align: center;
  margin-right: 2mm;
  border-radius: 5px;
  background-color: #fff;
}
.main .line-section{
  padding:1mm 12mm 4mm 12mm; 
  display: flex;
  margin: 0 5%;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.line{
  background-color: #e89267;
  height: 4px;
  width: 25%;
  border-radius: 5px;
}
.line1{
  background-color: #10514b;
  height: 4px;
  width: 25%;
  border-radius: 5px;
}
.middle{
  padding: 2mm 10mm ;
}
.subject p{
  font-size: 15px !important;
  font-weight: 500;
  line-height: 1.2;
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
  color: #10514b;
}
.last{
  padding: 2mm 10mm ;
}
.last p{
  font-size: 15px !important;
  font-weight: 500;  
}
@media print {
  body, .page {
    margin: 0;
    box-shadow: none;
  }
}
</style>
