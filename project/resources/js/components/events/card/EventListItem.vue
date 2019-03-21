<template>
    <event-item :event="event"></event-item>
</template>

<script>
    import EventItem from "./EventCard.vue";

    export default {
        props: ['event'],
        data: function () {

            return {}
        },
        computed: {},
        mounted() {
        },
        methods: {},
        components: {
            EventItem
        }
    }
</script>

<style scoped lang="scss">
    .card {
        width: 22rem;
        height: 17em;
        margin: 1em;
    }
</style>
