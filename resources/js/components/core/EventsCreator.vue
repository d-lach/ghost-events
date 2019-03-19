<template>
    <div class="event-form">
        <div class="row justify-content-center">
            <div class="subpage-header h2">
                {{ isInEditorMode ? "Edit" : "Create" }} Event
            </div>
        </div>
        <div class="row justify-content-center">
            <form @submit.prevent="submit" class="event-form">
                <div class="row">
                    <div class="col-md-6 event-info event-data">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" v-model="event.name"/>
                            <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control long-text-input" name="description" id="description"
                                   v-model="event.description"/>
                            <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}
                           </div>
                        </div>
                    </div>
                    <div class="col-md-6 event-details event-data">
                        <div class="form-group row">
                            <label for="maxGuests" class="col-lg-4 col-form-label col-form-label-md">Guests</label>
                            <div class="col-lg-8">
                                <input type="number" class="form-control number-input" name="maxGuests" id="maxGuests"
                                       v-model="event.maxGuests"/>
                                <div v-if="errors && errors.maxGuests" class="text-danger">{{ errors.maxGuests[0] }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="duration" class="col-lg-4 col-form-label col-form-label-md">Duration</label>
                            <div class="col-lg-8">
                                <date-picker id="duration"
                                             @input="updateDuration"
                                             v-model="duration"
                                             width="20em"
                                             type="datetime"
                                             range
                                             :not-before="new Date()"
                                             format="YY-MM-DD HH:mm"
                                             :shortcuts="dateTimeSettings.shortcuts"
                                             :lang="dateTimeSettings.lang"
                                             :time-picker-options="dateTimeSettings.timeOptions"></date-picker>
                                <div v-if="errors && errors.starts_at" class="text-danger">{{ errors.starts_at[0] }}

                                </div>
                            </div>
                            <div v-if="errors && errors.ends_at" class="text-danger">{{ errors.ends_at[0] }}</div>
                        </div>
                        <div class="form-group row">
                            <label for="closes_at" class="col-lg-4 col-form-label col-form-label-md">Registration open till</label>
                            <div class="col-lg-8">
                                <date-picker id="closes_at"
                                             v-model="openTill" type="datetime"
                                             :not-before="new Date()"
                                             :not-after="endsAtObj"
                                             @input="updateCloseDate"
                                             :lang="dateTimeSettings.lang"
                                             :time-picker-options="dateTimeSettings.timeOptions"></date-picker>
                                <div v-if="errors && errors.closes_at" class="text-danger">{{ errors.closes_at[0] }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="private" class="col-md-4 col-form-label col-form-label-md">
                                {{ private ? "Private" : "Public"}}</label>
                            <div class="col-md-8">
                                <label class="form-check-label switch">
                                    <input type="checkbox" name="private" id="private"
                                           v-model="private"/>
                                    <span class="slider"></span>
                                </label>
                                <div v-if="errors && errors.private" class="text-danger">{{ errors.private[0] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col event-address event-data">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="street">Street</label>
                                    <input type="text" class="form-control" name="street" id="street"
                                           v-model="event.street"/>
                                    <div v-if="errors && errors.street" class="text-danger">{{ errors.street[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="zipCode">Zip code</label>
                                    <input type="text" class="form-control" name="zipCode" id="zipCode"
                                           v-model="event.zipCode"/>
                                    <div v-if="errors && errors.zipCode" class="text-danger">
                                        {{ errors.zipCode[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city" id="city"
                                           v-model="event.city"/>
                                    <div v-if="errors && errors.city" class="text-danger">{{ errors.city[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <event-location :event="event"></event-location>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary">{{ isInEditorMode ? "update" : "save" }}

                    </button>
                </div>
                <div class="row guests-controller-wrapper" v-if="isInEditorMode">
                    <guests-controller :event="event" :guests="guests"></guests-controller>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
    import EventsService from '~/services/EventsService';
    import EventLocation from '../events/EventLocator.vue';
    import GuestsController from "../events/GuestsController.vue";
    import DatePicker from 'vue2-datepicker';
    import moment from 'moment';

    export default {
        props: {
            event: {
                default: function () {
                    return {
                        closes_at: "",
                        starts_at: "",
                        ends_at: "",
                        latitude: 52.231838,
                        longitude: 21.0038063,
                        private: false,
                    };
                }
            },
            guests: {
                default: function () {
                    return []
                }
            }
        },
        data() {
            return {
                //event: {},
                duration: [],
                openTill: "",
                startsAtObj: "",
                endsAtObj: "",
                private: this.event.private,
                errors: {},
                success: false,
                loaded: true,
                dateTimeFormat: 'YYYY-MM-DD HH:mm',
                dateTimeSettings: {
                    lang: {
                        days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                        placeholder: {
                            date: 'Select Date',
                            dateRange: 'Select Date Range'
                        }
                    },
                    shortcuts: [
                        {
                            text: 'Today',
                            onClick: () => {
                                this.duration = [new Date(), new Date()]
                            }
                        }
                    ],
                    timeOptions: {
                        start: '00:00',
                        step: '00:30',
                        end: '23:30'
                    }
                },

            }
        },
        computed: {
            isInEditorMode() {
                return !!this.event.id;
            },
        },
        mounted() {
            this.startsAtObj = this.event.starts_at ? new Date(this.event.starts_at) : null;
            this.endsAtObj = this.event.ends_at ? new Date(this.event.ends_at) : null;
            this.openTill = this.event.closes_at ? new Date(this.event.closes_at) : null;
            this.duration.push(this.startsAtObj, this.endsAtObj);
        },
        methods: {
            checkedD(e) {
                console.log((this.event.private ? "y" : "n"), e);
            },
            updateDuration(range) {
                this.event.starts_at = moment(range[0]).format(this.dateTimeFormat);
                this.event.ends_at = moment(range[1]).format(this.dateTimeFormat);
            },
            updateCloseDate(dateTime){
                this.event.closes_at = moment(dateTime).format(this.dateTimeFormat);
            },
            submit() {
                if (this.loaded) {
                    this.loaded = false;
                    this.success = false;
                    this.errors = {};
                    EventsService.save(this.event).then(({data, succcess, status}) => {
                        this.loaded = true;
                        this.success = succcess;

                        if (!succcess && status === 422) {
                            this.errors = data.errors || {};
                            return;
                        }

                    });
                }
            },
        },
        components: {
            DatePicker, GuestsController, EventLocation
        }
    }
</script>

<style scoped>
    .event-data {
        /*border-style: solid;
        border-width: 1px;
        border-radius: 4px;*/
        /*padding: 0.15em;
        margin: 0.15em;*/
        /*border-left-width: 10px;*/
        /*border-right-width: 10px;*/
        /*border-color: #34346747;*/
    }

    .number-input {
        width: 4em;
    }
    .map-wrapper {
        height: 20em;
    }

    .event-form {
        margin: 1.5em;
    }

    form {
        width: 100%;
        margin: 0.5em;
    }

    label {
        font-weight: 700;
    }

    .guests-controller-wrapper {
        margin-top: 1em;
    }

    .long-text-input {
        height: 7em;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
</style>