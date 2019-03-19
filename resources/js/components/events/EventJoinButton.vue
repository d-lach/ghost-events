<template>
    <span v-if="" class="join-button">
        <button v-if="!isGuest" :disabled="canJoin" @click="join()" type="button" class="btn btn-success">
            <b-spinner v-if="event.canJoinLoading" small type="grow"></b-spinner>
            Join
        </button>
        <button v-else @click="leave()" type="button" class="btn btn-danger">
            <b-spinner v-if="event.canJoinLoading" small type="grow"></b-spinner>
            Leave
        </button>
    </span>
</template>

<script>
    import moment from 'moment';
    import EventsService from "~/services/EventsService";
    import User from "~/User";

    export default {
        props: ['event'],
        data: function () {
            return {
                guests: this.event.guests,
                canJoinLoading: false,
            }
        },
        watch: {
            event: {
                handler (newEvent) {
                    this.refresh();
//                    console.log('property updated', newEvent.host, "vs", this.event.host);
                },
                deep: true
            }
        },
        computed: {
            canJoin: function() {
                return !User.isLoggedIn() || this.hasMaxGuests;
            },
            isGuest() {
                return this.guests.indexOf(User.id) > -1;
            },
            hasMaxGuests() {
                return this.guests.length >= this.event.maxGuests
            }
        },
        mounted() {
        },
        methods: {
            refresh() {
                this.guests = this.event.guests;
                this.canJoinLoading = false;
            },
            join() {
                if (this.hasMaxGuests)
                    return;

                this.event.canJoinLoading = true;
                EventsService.join(this.event.id).then(({success}) => {
                    if (success)
                        this.guests.push(User.id);

                    this.event.canJoinLoading = false;
                    this.$emit("joined");
                })
            },
            leave () {
                this.event.canJoinLoading = true;

                EventsService.leave(this.event.id).then(({success}) => {
                    if (success) {
                        let guestIndex = this.guests.indexOf(User.id);

                        if (guestIndex > -1)
                            this.guests.splice(guestIndex, 1);
                    }

                    this.event.canJoinLoading = false;
                    this.$emit("left");
                });
            },
        }
    }
</script>

<style scoped>

</style>
