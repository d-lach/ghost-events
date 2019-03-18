<template>
    <div>
        <b-table responsive striped hover
                 :items="events"
                 :fields="fields"
                 :sort-compare="tableSort">
            <template slot="closes_at" slot-scope="data">
                {{ data.item.closes_at | standardDate }}
            </template>
            <template slot="starts_at" slot-scope="data">
                {{ data.item.starts_at | standardDate }}
            </template>
            <template slot="ends_at" slot-scope="data">
                {{ data.item.ends_at | standardDate }}
            </template>
            <template slot="guests" slot-scope="data">
                {{ data.item.numberOfGuests }} / {{ data.item.maxGuests }}
            </template>
            <template slot="controls" slot-scope="data">
                <!--<a v-if="data.item.isEditable" :href="'/events/' + data.item.id + '/edit'">Edit</a>-->
                <!--<a v-if="data.item.canJoin" :href="'/events/' + data.item.id + '/join'">Edit</a>-->
                <button v-if="data.item.canEdit" @click="edit(data.item.id)" type="button" class="btn btn-info">Edit
                </button>
                <button v-if="data.item.canJoin" @click="join(data.item.id)" type="button" class="btn btn-success">
                    <b-spinner v-if="data.item.canJoinLoading" small type="grow"></b-spinner>
                    Join
                </button>
                <button v-if="!(data.item.canJoin)" @click="leave(data.item.id)" type="button" class="btn btn-danger">
                    <b-spinner v-if="data.item.canJoinLoading" small type="grow"></b-spinner>
                    Leave
                </button>
            </template>
        </b-table>
    </div>
</template>

<script>

    import Formatter from "~/Utilities/Formatting";
    import EventsService from "~/services/EventsService";

    export default {
        props: ['events'],
        data() {
            return {
                fields: [
                    {
                        key: 'name',
                        sortable: true
                    },
                    {
                        key: 'guests',
                        label: 'Guests',
                        sortable: true,
                    },
                    {
                        key: 'starts_at',
                        label: 'Starts',
                        sortable: true,
                    },
                    {
                        key: 'ends_at',
                        label: 'Ends',
                        sortable: true,
                    },
                    {
                        key: 'closes_at',
                        label: 'Registration closes',
                        sortable: true,
                    },
                    {
                        key: 'city',
                        sortable: true
                    },
                    {
                        key: 'controls',
                        sortable: false
                    }
                ],
            }
        },
        mounted() {
            this.events.forEach((event) => {
                event.starts_at = Formatter.parseEventDate(event.starts_at);
                event.ends_at = Formatter.parseEventDate(event.ends_at);
                event.closes_at = Formatter.parseEventDate(event.closes_at);

                event.canEdit = false;
                event.canJoin = true;
                event.canJoinLoading = false;
            });

            EventsService.getUserEventsIds().then(({data: events, success}) => {
                if (!success)
                    return;

                for (let hostedId of events.hosted) {
                    let hostedEvent = this.findEvent(hostedId);
                    if (hostedEvent)
                        this.findEvent(hostedId).canEdit = true;
                }

                for (let attendedId of events.attended) {
                    let attendedEvent = this.findEvent(attendedId);
                    if (attendedEvent)
                        this.findEvent(attendedId).canJoin = false;
                }

                this.$forceUpdate();
            });
        },
        methods: {
            findEvent(id) {
                return this.events.find((e) => {
                    return e.id === id;
                });
            },
            tableSort(a, b, key) {
                if (typeof a[key] === 'number' && typeof b[key] === 'number') {
                    // If both compared fields are native numbers
                    return a[key] < b[key] ? -1 : a[key] > b[key] ? 1 : 0
                } else if (key === "guests") {
//                    let proportionA = a.numberOfGuests / a.maxGuests;
//                    let proportionB = b.numberOfGuests / b.maxGuests;
                    return a.numberOfGuests < b.numberOfGuests ? -1 : a.numberOfGuests > b.numberOfGuests ? 1 : 0;
                    // proportionA < proportionB ? -1 : proportionA > proportionB ? 1 : 0;
                } else if (key === "starts_at" || key === "ends_at" || key === "closes_at") {
                    return a[key].isAfter(b[key]) ? 1 : -1;
                } else {
                    // Stringify the field data and use String.localeCompare
                    return toString(a[key]).localeCompare(toString(b[key]), undefined, {
                        numeric: true
                    })
                }
            },
            join(eventId) {
                let event = this.findEvent(eventId);
                event.canJoinLoading = true;
                EventsService.join(eventId).then(({success}) => {
                    let event = this.findEvent(eventId);

                    if (success)
                        event.canJoin = false;

                        event.canJoinLoading = false;
                    this.$forceUpdate();

                })
            },
            leave (eventId) {
                let event = this.findEvent(eventId);
                event.canJoinLoading = true;

                EventsService.leave(eventId).then(({success}) => {
                    let event = this.findEvent(eventId);
                    if (success)
                        event.canJoin = true;
                        event.canJoinLoading = false;
                    this.$forceUpdate();

                });
            },
            edit(eventId) {
                window.location.href = '/events/' + eventId + '/edit';
            }
        },
        components: {},
    }
</script>
