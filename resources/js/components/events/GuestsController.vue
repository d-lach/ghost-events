<template>
    <div class="col">
        <div class="row align-content-center">
            Guests
        </div>
        <div class="row">
            <b-table responsive striped hover
                     :items="guests"
                     :fields="fields">
                <template slot="full-name" slot-scope="data">
                    {{ data.item.name | name }}
                </template>
                <template slot="gender" slot-scope="data">
                    {{ data.item.gender | gender }}
                </template>
                <template slot="age" slot-scope="data">
                    {{ data.item.age}}
                </template>
                <template slot="controls" slot-scope="data">
                    <button v-if="isGuest(data.item)" :disabled="reachedGuestsLimit" @click="add(data.item)"
                            type="button" class="btn btn-success">
                        Add
                    </button>
                    <button v-else @click="remove(data.item)" type="button" class="btn btn-outline-danger">
                        Remove
                    </button>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>

    import Formatter from "~/Utilities/Formatting";
    import EventsService from "~/services/EventsService";
    import User from "~/User";

    export default {
        props: ['event', 'guests'],
        data() {
            return {
                currentGuests: this.event.guests,
//                eventsWorking: new Map(),
                fields: [
                    {
                        key: 'full-name',
                        sortable: true
                    },
                    {
                        key: 'gender',
//                        label: 'Gender',
                        sortable: true,
                    },
                    {
                        key: 'age',
//                        label: 'Gender',
                        sortable: true,
                    },
                    {
                        key: 'controls',
                        sortable: false
                    }
                ],
            }
        },
        computed: {
            reachedGuestsLimit() {
                return this.currentGuests.length >= this.event.maxGuests;
            }
        },
        mounted() {
        },
        methods: {
            isGuest(user) {
//                console.log(user.id, "vs", this.currentGuests);
                return this.currentGuests.indexOf(user.id) < 0;
            },
            add(user) {
                this.currentGuests.push(user.id);
                user._rowVariant = '';
                EventsService.addGuest(this.event.id, user.id);
            },
            remove(user) {
                console.log("going to remove", user.id);
                let userIndex = this.currentGuests.indexOf(user.id);
                user._rowVariant = 'danger';
                if (userIndex < 0)
                    return;

                this.currentGuests.splice(userIndex, 1);
                EventsService.removeGuest(this.event.id, user.id);
            }
        },

        components: {},
    }
</script>
