<template>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{ event.name }}
                </div>
                <div class="col text-right">
                    <span class="number-of-guests">{{ event.numberOfGuests }}</span> /
                    <span class="max-number-of-guests">{{ event.maxGuests }} </span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span v-if="daysToStart > 1">Starts in {{ daysToStart }} days</span>
                    <span v-else-if="daysToStart === 1"> Starts tomorrow</span>
                    <span v-else> {{ timeToStart }} till start</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ event.description | text }}
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['event'],
        computed: {
            daysToStart() {
                return moment(this.event.starts_at, 'YYYY-MM-DD HH:mm').diff(moment(), 'days');
            },
            timeToStart() {
                let remaining = moment.duration(moment(this.event.starts_at, 'YYYY-MM-DD HH:mm').diff(moment()));
                if (remaining.hours() > 1)
                    return "about " + remaining.hours() + " hours";
                else if (remaining.hours() === 1) {
                    return ">1 hour"
                } else {
                    return remaining.minutes() + " minutes";
                }
            },
            msToStart() {
                return Math.abs(Date.now() - Date.parse(this.event.starts_at));
            },
        },
        mounted() {
        },
        methods: {}
    }
</script>

<style scoped>

    .card {
        width: 22rem;
        height: 17em;
        /*max-width: 20em;*/
        margin: 1em;
        /*overflow: hidden;*/
    }

    .card-body{
        overflow: auto;
    }
</style>
