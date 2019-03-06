<template>
    <div class="container">
        <!--<div style="padding: 20px">
            My geolocation: {{ geolocPosition }}
        </div>-->
        <vl-map :load-tiles-while-animating="true" :load-tiles-while-interacting="true">
            <vl-view :zoom.sync="zoom" :center.sync="center" :rotation.sync="rotation"></vl-view>
            <vl-geoloc @update:position="setBaseLocationAt($event)">
                <template slot-scope="geoloc">
                    <vl-feature v-if="geoloc.position" id="position-feature">
                        <vl-geom-point :coordinates="geoloc.position"></vl-geom-point>
                        <vl-style-box>
                            <vl-style-icon src="/img/marker.png" :scale="0.1" :anchor="[0.5, 1]"></vl-style-icon>
                        </vl-style-box>
                    </vl-feature>
                </template>
            </vl-geoloc>

            <vl-layer-tile id="osm">
                <vl-source-osm></vl-source-osm>
            </vl-layer-tile>
        </vl-map>
    </div>
</template>

<script>
    import EventsMockup from "../../mockup/EventsMockup";

    export default {
        data() {
            return {
                events: [],
                zoom: 2,
                center: [0, 0],
                rotation: 0,
                geolocPosition: undefined,
            }
        },
        mounted() {
            for (let i = 0; i < 10; i++) {
                this.events.push(EventsMockup.randomEvent);
            }
        },
        methods: {
            setBaseLocationAt(coords) {
                this.geolocPosition = coords;
                this.center = coords;
                this.zoom = 12;
            }
        },
        components: {},
    }
</script>

<style scoped>
    .container {
        width: 100%;
        height: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        /*max-width: 20em;*/
        /*margin: 1em;*/
    }
</style>
