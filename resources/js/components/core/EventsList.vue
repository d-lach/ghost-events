<template>
    <div class="container">
        <div class="row justify-content-center">
            <event-card v-for="(event, index) in events" :key="index" :event="event"></event-card>
        </div>
    </div>
</template>

<script>
    import EventCard from "../events/EventCard.vue";
    import EventsMockup from "../../mockup/EventsMockup";

    export default {
        data() {
            return {
                events: []
            }
        },
        mounted() {
            for (let i = 0; i < 10; i++) {
                this.events.push(EventsMockup.randomEvent);
            }
        },
        methods: {

        },
         components: {
                    EventCard
                },
    }
</script>
