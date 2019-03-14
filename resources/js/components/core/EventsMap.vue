<template>
    <div class="d-flex flex-grow-1">
        <event-card v-if="anyActive" :event="activeEvent"></event-card>
        <GmapMap
                :center="center"
                :zoom="zoom"
                :map-type-id="mapType"
                style="width: 100%; height: 100%"
        >
            <GmapCluster>
                <gmap-marker v-if="event.marker.enabled"
                             :position="event.marker.position"
                             :opacity="event.marker.opacity"
                             :draggable="event.marker.draggable"
                             @click="openEvent(event)"
                             v-for="event in events"
                             :key="event.id">
                </gmap-marker>
            </GmapCluster>
        </GmapMap>
    </div>

    <!--@rightclick="event.marker.rightClicked++"-->
    <!--@dragend="event.marker.dragended++"-->
    <!--@position_changed="updateChild(m, 'position', $event)"-->
</template>

<script>
    import EventCard from "../events/EventCard.vue";
    import EventsService from "~/services/EventsService";
    import EventsMockup from "~/mockup/EventsMockup";
    import Geolocator from "~/Utilities/Geolocator";

    export default {
        data() {
            return {
                activeEvent: null,
                events: [],
                zoom: 6,
                center: {lat: 52.231838, lng: 21.0038063},
                rotation: 0,
                geolocPosition: undefined,
                mapType: "roadmap",
            }
        },
        mounted() {
            EventsService.getAll().then((results) => {
                this.events = results.map(this.setupMarker.bind(this));
            });
            this.centerAtHome();
        },
        computed: {
            anyActive() {
                return this.activeEvent !== null;
            }
        },
        methods: {
            openEvent(event) {
                if (this.activeEvent && this.activeEvent.id === event.id) {
                    return;
                }
                this.closeActiveEvent();

                this.activeEvent = event;
            },
            closeActiveEvent() {
                if (!this.activeEvent)
                    return;

                this.activeEvent = null;
            },
            setupMarker(event) {
                event.marker = {
                    id: this.lastId,
                    position: {
                        lat: event.latitude,
                        lng: event.longitude
                    },
                    opacity: 1,
                    draggable: true,
                    enabled: true,
                    clicked: 0,
                    rightClicked: 0,
                    dragended: 0,
                    ifw: false,
                    ifw2text: 'This text is bad please change me :( '
                };
                return event;

            },
            setBaseLocationAt(x, y, z = 12) {
                this.center = {lat: x, lng: y};
                this.zoom = z;
            },
            centerAtHome() {
                this.findHomeLocationViaBrowser()
                    .catch(this.findHomeLocationViaApi.bind(this))
                    .then((position) => this.setBaseLocationAt(position.coords.latitude, position.coords.longitude, 12))
                    .catch(this.jumpToCrashSite.bind(this));
            },
            findHomeLocationViaBrowser() {
                return new Promise((resolve, reject) => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(resolve, reject);
                    } else
                        reject();
                })
            },
            findHomeLocationViaApi() {
                return Geolocator.locate();
            },
            jumpToCrashSite() { // just for fun :)
                this.setBaseLocationAt(31.254306, -24.258472, 9);
                this.mapType = "satellite";
            }
        },
        components: {
            EventCard
        },
    }
</script>

<style scoped>

</style>
