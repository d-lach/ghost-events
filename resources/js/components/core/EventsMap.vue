<template>
    <div class="d-flex flex-grow-1">
        <GmapMap
                :center="center"
                :zoom="zoom"
                :map-type-id="mapType"
                style="width: 100%; height: 100%"
        >
        </GmapMap>
    </div>
</template>

<script>
    import EventsMockup from "../../mockup/EventsMockup";
    import Geolocator from "../../Utilities/Geolocator";

    export default {
        data() {
            return {
                events: [],
                zoom: 6,
                center: {lat: 52.231838, lng: 21.0038063},
                rotation: 0,
                geolocPosition: undefined,
                mapType: "roadmap",
            }
        },
        mounted() {
            for (let i = 0; i < 10; i++) {
                this.events.push(EventsMockup.randomEvent);
            }

            this.centerAtHome();
        },
        methods: {
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
        components: {},
    }
</script>

<style scoped>

</style>
