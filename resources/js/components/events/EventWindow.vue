<template>
    <div class="wrapper">
        <event-item :event="event"></event-item>
    </div>
</template>

<script>
    import moment from 'moment';
    import EventItem from "../events/EventItem.vue";

    export default {
        props: ['event'],
        data: function () {

            return {
            }
        },
        computed: {
        },
        mounted() {
        },
        methods: {
            //reset()
        },
        components: {
            EventItem
        }
    }
</script>

<style scoped>

    .wrapper {
        position: absolute;
        z-index: 1000;
        margin: 1em;
    }

    .card {
        background: #fff;
        border: #000 solid 1px;

        max-width: 700px;
        width: 50vh;
        height: 65vh;
    }

</style>

<style>



</style>
