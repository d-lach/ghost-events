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
                <button v-if="data.item.canEdit" @click="edit(data.item.id)" type="button" class="btn btn-info">Edit
                </button>
                <event-join-button :event="data.item"></event-join-button>
            </template>
        </b-table>
    </div>
</template>

<script>

    import Formatter from "~/Utilities/Formatting";
    import EventJoinButton from "../events/EventJoinButton.vue";
    import EventsService from "~/services/EventsService";

    export default {
        props: ['events'],
        data() {
            return {
//                eventsWorking: new Map(),
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
            });
        },
        methods: {
            tableSort(a, b, key) {
                if (typeof a[key] === 'number' && typeof b[key] === 'number') {
                    // If both compared fields are native numbers
                    return a[key] < b[key] ? -1 : a[key] > b[key] ? 1 : 0
                } else if (key === "guests") {
//                    let proportionA = a.numberOfGuests / a.maxGuests;
//                    let proportionB = b.numberOfGuests / b.maxGuests;
                    // proportionA < proportionB ? -1 : proportionA > proportionB ? 1 : 0;
                    return a.numberOfGuests < b.numberOfGuests ? -1 : a.numberOfGuests > b.numberOfGuests ? 1 : 0;
                } else if (key === "starts_at" || key === "ends_at" || key === "closes_at") {
                    return a[key].isAfter(b[key]) ? 1 : -1;
                } else {
                    // Stringify the field data and use String.localeCompare
                    return toString(a[key]).localeCompare(toString(b[key]), undefined, {
                        numeric: true
                    })
                }
            },
            edit(eventId) {
                window.location.href = '/events/' + eventId + '/edit';
            }
        },
        components: {
            EventJoinButton
        },
    }
</script>
