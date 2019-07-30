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
                <template v-if="allowModifications" slot="controls" slot-scope="data">
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
        <div class="row">
            <div v-if="inviting" class="invitation-wrapper">
                <input id="invitation-email" type="email" name="invitation-email" value="" v-model="invitationEmail">
                <button @click="invite()" type="button" class="btn btn-primary invitation-button">
                    Invite
                </button>
            </div>
            <div v-else class="align-content-center">
                <button @click="openInvitation()" type="button" class="btn btn-primary invitation-button">
                    Add user
                </button>
            </div>
        </div>
    </div>
</template>

<script>

    import EventsService from "~/services/EventsService";
    import InvitationsService from "../../services/InvitationsService";

    export default {
        props: {
            event: Object,
            guests: Array,
            allowModifications: {
                default: true
            }
        },
        data() {
            return {
                inviting: false,
                invitationEmail: "",
                currentGuests: this.event.guests,
                fields: [
                    {
                        key: 'full-name',
                        sortable: true
                    },
                    {
                        key: 'gender',
                        sortable: true,
                    },
                    {
                        key: 'age',
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
                return this.currentGuests.indexOf(user.id) < 0;
            },
            add(user) {
                this.currentGuests.push(user.id);
                user._rowVariant = '';
                EventsService.addGuest(this.event.id, user.id);
            },
            remove(user) {
                let userIndex = this.currentGuests.indexOf(user.id);
                user._rowVariant = 'danger';
                if (userIndex < 0)
                    return;

                this.currentGuests.splice(userIndex, 1);
                EventsService.removeGuest(this.event.id, user.id);
            },
            invite() {
                InvitationsService.invite(this.event.id, this.invitationEmail).then((response) => {
                    console.log(response);
                    this.invitationEmail = "";
                    this.inviting = false;
                });
            },
            openInvitation() {
                this.inviting = true;
            }
        },
        components: {},
    }
</script>

<style lang="less">
    .invitation-button {
        margin: auto;
    }
</style>
