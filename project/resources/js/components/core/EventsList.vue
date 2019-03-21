<template>
    <div>
        <event-list-item v-for="(event, index) in events" :key="index" :event="event"></event-list-item>
    </div>
</template>

<script>
    import EventListItem from "../events/card/EventListItem.vue";

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
            EventListItem
        },
    }
</script>
