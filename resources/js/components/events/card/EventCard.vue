<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{ event.name }}
                </div>
                <div class="col text-right">
                    <span class="number-of-guests">{{ currentNumberOfGuests }}</span> /
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
        <div class="card-footer">
            <event-join-button  ref="joinControl" :event="event"
                                @joined="currentNumberOfGuests++;"
                                @left="currentNumberOfGuests--;"></event-join-button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import EventJoinButton from '../EventJoinButton.vue';

    export default {
        props: ['event'],
        data: function() {

            return {
               currentNumberOfGuests: this.event.guests.length,
            }
        },
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
        methods: {
        },
        components: {
            EventJoinButton
        }
    }
</script>

<style scoped>
    .card-body {
        overflow: auto;
    }
</style>
