<template>
    <div>
        <event-abstract v-for="(event, index) in events" :key="index" :event="event"></event-abstract>
    </div>
</template>

<script>
    import EventAbstract from "../events/EventAbstract.vue";
    import EventsMockup from "~/mockup/EventsMockup";

    export default {
        props: ['events'],
        data() {
            return {

            }
        },
        mounted() {
        },
        methods: {
        },
        components: {
            EventAbstract
        },
    }
</script>
