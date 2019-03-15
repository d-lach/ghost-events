<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" v-model="event.name"/>
            <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" name="description" id="description" v-model="event.description"/>
            <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div>
        </div>

        <div class="form-group">
            <label for="maxGuests">Guests</label>
            <input type="number" class="form-control" name="maxGuests" id="maxGuests" v-model="event.maxGuests"/>
            <div v-if="errors && errors.maxGuests" class="text-danger">{{ errors.maxGuests[0] }}</div>
        </div>

        <div class="form-group">
            <label for="duration">Duration</label>
            <date-picker id="duration"
                         v-model="duration"
                         type="datetime"
                         range
                         :not-before="new Date()"
                         format="YY-MM-DD HH:mm"
                         :shortcuts="dateTimeSettings.shortcuts"
                         :lang="dateTimeSettings.lang"
                         :time-picker-options="dateTimeSettings.timeOptions"></date-picker>
            <!--
            <input type="datetime-local" class="form-control" name="starts_at" id="starts_at" v-model="event.starts_at"/>
            -->
            <div v-if="errors && errors.starts_at" class="text-danger">{{ errors.starts_at[0] }}</div>
            <div v-if="errors && errors.ends_at" class="text-danger">{{ errors.ends_at[0] }}</div>
        </div>

        <!--   <div class="form-group">
               &lt;!&ndash;
               <label for="ends_at">Ends at</label>
               <input type="datetime-local" class="form-control" name="ends_at" id="ends_at" v-model="event.ends_at"/>
               &ndash;&gt;
               <date-picker v-model="event.ends_at"
                            type="datetime"
                            :lang="dateTimeSettings.lang"
                            :time-picker-options="dateTimeSettings.timeOptions"></date-picker>
               <div v-if="errors && errors.ends_at" class="text-danger">{{ errors.ends_at[0] }}</div>
           </div>-->

        <div class="form-group">
            <!--
            <label for="closes_at">Registration open till</label>
             <input type="datetime-local" class="form-control" name="closes_at" id="closes_at"
                    v-model="event.closes_at"/>
                    -->
            <label for="closes_at">Registration open till</label>
            <date-picker id="closes_at"
                         v-model="openTill" type="datetime"
                         :not-before="new Date()"
                         :not-after="event.ends_at"
                         :lang="dateTimeSettings.lang"
                         :time-picker-options="dateTimeSettings.timeOptions"></date-picker>
            <div v-if="errors && errors.closes_at" class="text-danger">{{ errors.closes_at[0] }}</div>
        </div>

        <div class="address">
            <div class="form-group">
                <label for="street">Street</label>
                <input type="text" class="form-control" name="street" id="street"
                       v-model="event.street"/>
                <div v-if="errors && errors.street" class="text-danger">{{ errors.street[0] }}</div>
            </div>
            <div class="form-group">
                <label for="zipCode">Zip code</label>
                <input type="text" class="form-control" name="zipCode" id="zipCode"
                       v-model="event.zipCode"/>
                <div v-if="errors && errors.zipCode" class="text-danger">{{ errors.zipCode[0] }}</div>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" name="city" id="city"
                       v-model="event.city"/>
                <div v-if="errors && errors.city" class="text-danger">{{ errors.city[0] }}</div>
            </div>
        </div>
        <div class="form-group">
            <label for="private">Private:</label>
            <input type="checkbox" class="form-control" name="private" id="private"
                   v-model="event.private"/>
            <div v-if="errors && errors.private" class="text-danger">{{ errors.private[0] }}</div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <!--
       'name' => ['string', 'max:255'],
       'description' => ['string', 'max:1600'],
       'maxGuests' => ['integer', 'min:1'],
       'starts_at' => ['date_format:Y-m-d H:i', 'after:today'],
       'ends_at' => ['date_format:Y-m-d H:i', 'after:starts_at'],
       'closes_at' => ['date_format:Y-m-d H:i', 'before_or_equal:ends_at'],
       'street' => ['string', 'min:3'],
       'city' => ['string', 'min:3'],
       'zipCode' => ['string', 'regex:/^[0-9]{2}-[0-9]{3}$/'], // zip code format 00-000
       'latitude' => ['numeric'],
       'longitude' => ['numeric'],
       'private' => ['boolean'],
    -->
</template>

<script>
    import EventsService from '~/services/EventsService';
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
            }
        },
        data() {
            return {
                //event: {},
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
            duration: {
                get: function () {
                    return [new Date(this.event.starts_at), new Date(this.event.ends_at)];
                },
                set: function (range) {
                    this.event.starts_at = moment(range[0]).format(this.dateTimeFormat);
                    this.event.ends_at = moment(range[1]).format(this.dateTimeFormat); //new Date(range[1]);
                }
            },
            openTill: {
                get: function () {
                    return new Date(this.event.closes_at);
                },
                set: function (range) {
                    this.event.closes_at = moment(range).format(this.dateTimeFormat);
                }
            }
        },
        methods: {
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

                        this.event = {}; //Clear input fields.
                    });
                }
            },
        },
        components: {
            DatePicker
        }
    }
</script>