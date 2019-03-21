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
                                             format="DD-MM-YY HH:mm"
                                             :shortcuts="dateTimeSettings.shortcuts"
                                             :lang="dateTimeSettings.lang"
                                             :time-picker-options="dateTimeSettings.timeOptions"
                                             :disabled="isArchival"
                                ></date-picker>
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
                                             format="DD-MM-YY HH:mm"
                                             @input="updateCloseDate"
                                             :lang="dateTimeSettings.lang"
                                             :time-picker-options="dateTimeSettings.timeOptions"
                                             :disabled="isArchival"
                                ></date-picker>
                                <div v-if="errors && errors.closes_at" class="text-danger">{{ errors.closes_at[0] }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="private" class="col-md-4 col-form-label col-form-label-md">
                                {{ event.private ? "Private" : "Public"}}</label>
                            <div class="col-md-8">
                                <label class="form-check-label switch">
                                    <input type="checkbox" name="private" id="private"
                                           v-model="event.private"/>
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
                                    <label for="address">Location</label>
                                    <GmapAutocomplete ref="addressInput" class="address-input" name="address"
                                                      id="address" :selectFirstOnEnter="true"
                                                      @place_changed="newAddress">
                                    </GmapAutocomplete>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <event-location :coords="coordinates"
                                                :address="address"
                                                @address-update="setAddressFromMap"
                                ></event-location>
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
            eventData: {
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
                event: {
                    ...this.eventData
                },
                duration: [],
                openTill: "",
                startsAtObj: "",
                endsAtObj: "",
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
                isArchival: false
            }
        },
        computed: {
            isInEditorMode() {
                return !!this.event.id;
            },
            address() {
                return {
                    street: this.event.street,
                    zipCode: this.event.zipCode,
                    city: this.event.city
                }
            },
            coordinates() {
                return {
                    latitude: this.event.latitude,
                    longitude: this.event.longitude
                }
            }
        },
        mounted() {
            this.startsAtObj = this.event.starts_at ? new Date(this.event.starts_at) : null;
            this.endsAtObj = this.event.ends_at ? new Date(this.event.ends_at) : null;
            this.openTill = this.event.closes_at ? new Date(this.event.closes_at) : null;
            this.duration.push(this.startsAtObj, this.endsAtObj);

            this.isArchival = (this.endsAtObj && this.endsAtObj < Date.now());
            this.updateAddressSearchBar();
        },
        methods: {
            newAddress(event) {
                this.event.street = [this.findInRawGoogleAddress(event, 'route')]
                    + " "
                    + this.findInRawGoogleAddress(event, 'street_number');//  event.
                this.event.city = this.findInRawGoogleAddress(event, 'locality');
                this.event.zipCode = this.findInRawGoogleAddress(event, 'postal_code');

                this.event.longitude = event.geometry.location.lng();
                this.event.latitude = event.geometry.location.lat();
            },
            findInRawGoogleAddress(addressEvent, componentType) {
                let found = addressEvent.address_components.find((component) => {
                    return component.types.indexOf(componentType) >= 0;
                });
                if (!found)
                    return "";

                return found.long_name;
            },
            updateAddressSearchBar() {
                document.getElementById('address').value = [
                    this.event.street,
                    this.event.zipCode,
                    this.event.city
                ].map(s => s.trim())
                    .filter(s => s.length > 0)
                    .join(", ");
            },
            setAddressFromMap(event) {
                this.event.street = event.address.street;
                this.event.city = event.address.city;
                this.event.zipCode = event.address.zipCode;

                this.event.latitude = event.coordinates.latitude;
                this.event.longitude = event.coordinates.longitude;

                this.updateAddressSearchBar();
            },
            updateDuration(range) {
                this.event.starts_at = moment(range[0]).format(this.dateTimeFormat);
                this.event.ends_at = moment(range[1]).format(this.dateTimeFormat);
            },
            updateCloseDate(dateTime){
                this.event.closes_at = moment(dateTime).format(this.dateTimeFormat);
            },
            submit() {
                if (!this.loaded)
                    return;

                this.loaded = false;
                this.success = false;
                this.errors = {};

                let toSubmit = {...this.event};
                if (this.isArchival) {
                    delete toSubmit.starts_at;
                    delete toSubmit.ends_at;
                    delete toSubmit.closes_at;
                }

                EventsService.save(toSubmit).then(({data, succcess, status}) => {
                    this.loaded = true;
                    this.success = succcess;

                    if (!succcess && status === 422) {
                        this.errors = data.errors || {};
                        return;
                    }
                });

            },
        },
        components: {
            DatePicker, GuestsController, EventLocation
        }
    }
</script>

<style scoped>
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

    .address-input {
        width: 100%;
        line-height: 1.5em;
        font-size: 1.15em;
        border-width: 0 0 4px 0;
        border-radius: 2px;
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