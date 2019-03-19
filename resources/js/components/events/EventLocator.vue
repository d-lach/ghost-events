<template>
    <div class="map-wrapper">
        {{ event.id ? event.id : "New" }}: ({{ position.lat | amount }}, {{ position.lng | amount }})

        <GmapMap
                :center="center"
                :zoom="zoom"
                :map-type-id="mapType"
                style="width: 100%; height: 100%">
            <gmap-marker :position="position"
                         :draggable="true"
                         @position_changed="updatePosition"
                         @dragend="updateAddress">
            </gmap-marker>
        </GmapMap>
    </div>

    <!--@rightclick="event.marker.rightClicked++"-->
    <!--@dragend="event.marker.dragended++"-->
    <!--@position_changed="updateChild(m, 'position', $event)"-->
</template>

<script>
    import EventWindow from "../events/EventWindow.vue";
    import EventsService from "~/services/EventsService";
    import Geolocator from "~/Utilities/Geolocator";

    export default {
        props: ['event'],
        data() {
            return {
                activeEvent: null,
                events: [],
                zoom: 6,
                position: {lat: 52.231838, lng: 21.0038063},
                rotation: 0,
                geolocPosition: undefined,
                mapType: "roadmap",
                center: {lat: 52.231838, lng: 21.0038063},
            }
        },
        mounted() {
            this.setupMarker(this.event);
            console.log(this.event);
            this.centerAtHome();
        },
        computed: {},
        methods: {
            updatePosition(event) {
                this.position.lat = event.lat();
                this.position.lng = event.lng();
            },
            updateAddress(event) {
                console.log(event.latLng.lat(), ",", event.latLng.lng());
                Geolocator.geocdeToAddress({lat: event.latLng.lat(), lng: event.latLng.lng()})
                    .then((address) => {
                        console.log("ok:", address);
                    }).catch((err) => {
                    console.log("nope:", err);
                });
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
            EventWindow
        },
    }
</script>

<style scoped>

    .map-wrapper {
        margin: 0 !important;
        width: 100%;
        height: 10em;
    }
</style>
