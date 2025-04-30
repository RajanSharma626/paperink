<template>
    <div class="page">
        <div class="main" id="page">
            <div class="top py-2 px-5" v-if="hasNameDetails" >

                <div class="name" v-if="cover.name">
                    {{ cover.name }}
                </div>



                <div class="profession" v-if="cover.jobTitle">
                    {{ cover.jobTitle }}
                </div>

            </div>
            <div class="middle" v-if="hasContactDetails">
                <div class="left" v-if="cover.email">
                    <p class="details m-0"><i class="icons bi-envelope"></i>{{ cover.email }} </p>
                </div>
                <div class="right" v-if="cover.phone">
                    <p class="details m-0"><i class="icons bi-phone "></i>{{ cover.phone }}</p>
                </div>
                <div class="right" v-if="cover.address">
                    <p class="details m-0"><i class="icons bi-geo-alt"></i> {{ cover.address }}</p>
                </div>
            </div>
            <div class="end">
                <div class="heading" v-if="cover.company">

                    <span class="to">To:</span>

                    <h6>{{ cover.company }}</h6>
                </div>
                <br v-if="cover.company">
                <div class="subject">

                    <p v-if="cover.hiringManager">Dear {{ cover.hiringManager }},</p>


                    <div v-if="cover.letter" v-html="cover.letter"></div>


                    <div class="last" v-if="cover.name">
                        <p>Sincerly</p>
                        <p> {{cover.name}} </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    cover: {
        type: Object,
        required: true
    }
})

const hasContactDetails = computed(() => {
    const r = props.cover;
    return r.email || r.phone || r.address;
});

const hasNameDetails = computed(() => {
    const r = props.cover;
    return r.name || r.jobTitle;
});


</script>

<style scoped>
.page {
    background: #fff;
    width: 210mm;
    min-height: 297mm;
    /* padding: 2mm; */
    margin: 10mm auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.main .top {
    /* height: 50px; */
    background-color: #697083;
    border-radius: 10px 10px 0 0;
    /* padding: 10mm 12mm; */
}

.main .top .name {
    font-size: 35px !important;
    color: #fff;
}

.main .top .profession {
    font-size: 20px !important;
    color: #a4c422;
    font-weight: 500;
}

.middle {
    /* height: 75px; */
    padding: 2mm 12mm;
    background-color: #50576a;
    display: flex;
    align-items: center;
    gap: 75px;
    line-height: 1.8;
}

.middle .details {
    font-size: 12px !important;
    color: #fff;
    font-weight: 400;
}

.middle .details .icons {
    padding-right: 8px;
    font-size: 12px !important;
}

.end {
    padding: 4mm 12mm;
}

.end .heading {
    line-height: 1.3;
}

.end .heading .to {
    font-size: 14px !important;
    color: #697083;
    font-style: italic;
}

.end .heading h6 {
    font-size: 17px !important;
    font-weight: 500;
}

.end .heading .date {
    font-size: 17px;
    color: #697083;
    font-style: italic;
}

.end .subject {
    margin-top: 5px;
}

.end .subject p {
    font-size: 15px !important;
    font-weight: 500;
}

.end .subject ul {
    font-size: 15px !important;
    line-height: 1.5;
    padding-left: 4mm;
    font-weight: 500;
}

.subject ul li::marker {
    color: #a4c422;
    font-size: 15px !important;
}

.image {
    height: 100%;
    /* height: 100%; */
}

.img {
    object-fit: cover;

}

.img img {
    height: 200px;
    width: 100%;
}

@media print {

    body,
    .page {
        margin: 0;
        box-shadow: none;
    }
}
</style>
