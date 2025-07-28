<template>
  <div class="page">
    <div class="main">
      <div class="top">
        <div class="name-section">
          <div v-if="cover.name" class="name">
            {{ cover.name }}
          </div>
          <div v-if="cover.jobTitle" class="profession">
            {{ cover.jobTitle }}
          </div>
        </div>
        <div class="details-section">
          <div class="left">
            <p v-if="cover.email" class="details"><i class="icons bi-envelope"></i>{{ cover.email }}</p>
            <p v-if="cover.phone" class="details"><i class="icons bi-phone"></i>{{ cover.phone }}</p>
          </div>
          <div class="right">
            <p v-if="cover.address" class="details"><i class="icons bi-geo-alt"></i>{{ cover.address }}</p>
            <p v-if="cover.linkedin" class="details"><i class="icons bi-linkedin"></i>{{ cover.linkedin }}</p>
          </div>
        </div>
      </div>

      <div class="middle">
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

      <div class="line-section">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
      </div>

      <div class="end">
        <div class="subject">
          <p v-if="cover.mr || cover.hiringManager">
            Dear {{ cover.mr || '' }}{{ cover.hiringManager || '' }},
          </p>
          <br>
          <p v-if="cover.letter" v-html="cover.letter"></p>
        </div>
      </div>

      <div class="line-section">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
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
@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

:root{
  --firstcolor:#f89536;
  --secondcolor:#8c8c8c;
  --thirdcolor:  #191919;
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
.main .top{
  padding: 4mm 8mm;
}
.top .name-section{
  padding: 1mm 6mm 3mm 6mm;
}
.name-section .name{
  font-size: 30px !important;
  color: #f89536;
}
.name-section .profession{
  font-size: 18px !important;
  color: #191919;
}
.top .details-section{
  display: flex;
  align-items: center;
  gap: 75px;
  line-height: 2;
  padding: 2mm 6mm 3mm 6mm;
  border-radius: 5px;
  color: #fff;
  background-color: #f89536;
}

.details{
  font-size: 12px !important;
  font-weight: 400;
}
.details .icons{
  padding-right: 8px;
  font-size: 12px !important;
}

.middle{
  padding: 2mm 14mm ;
}
.middle .heading{
  line-height: 1.2;
}
.heading .to{
  font-size: 14px !important;
  color: #8c8c8c;
  font-style: italic;
}
.heading h6{
  font-size: 17px !important;
  font-weight: 500;
}

.line-section{
  padding:5mm 8mm; 
  display: flex;
  align-items: center;
  justify-content: center;
}

.line1{
  background-color: #f89536;
  height: 4px;
  width: 35%;
  border-radius: 5px 0 0 5px;

}
.line2{
  background-color: #191919;
  height: 4px;
  width: 35%;
  border-radius: 5px;
}
.line3{
  background-color: #8c8c8c;
  height: 4px;
  width: 35%;
  border-radius: 0 5px 5px 0;
}
.end{
  padding: 2mm 14mm ;
}

.subject p{
  font-size: 15px !important;
  font-weight: 500;
  line-height: 1.2;
}
.subject ol{
  font-size: 15px !important;
  line-height: 1.5;
  font-weight: 500;
  padding-left: 4mm;
  
}
.subject ol li{
  padding-left: 2mm;
}
.subject ol li::marker{
  font-size: 14px !important;

}
.last{
  padding: 2mm 14mm ;
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
