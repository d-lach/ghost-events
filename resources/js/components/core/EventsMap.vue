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
            Geolocator.locate()
                .then(this.initializeAtLocation.bind(this))
                .catch(this.jumpToCrashSite.bind(this));
        },
        methods: {
            setBaseLocationAt(x, y, z = 12) {
                this.center = {lat: x, lng: y};
                this.zoom = z;
            },
            initializeAtLocation(location) {
                this.home = location;
                this.setBaseLocationAt(location.coords.latitude, location.coords.longitude, 12)
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
