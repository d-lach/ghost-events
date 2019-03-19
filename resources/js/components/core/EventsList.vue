<template>
    <div>
        <event-card v-for="(event, index) in events" :key="index" :event="event"></event-card>
    </div>
</template>

<script>
    import EventCard from "../events/EventCard.vue";

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
            EventCard
        },
    }
</script>
