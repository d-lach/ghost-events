<template>
    <div class="map-wrapper">
        <div class="debug">
            ({{ currentCoords.lat | amount }}, {{ currentCoords.lng | amount }})



        </div>
        <GmapMap
                :center="center"
                :zoom="zoom"
                :map-type-id="mapType"
                style="width: 100%; height: 100%">
            <gmap-marker :position="currentCoords"
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
    import EventsService from "~/services/EventsService";
    import Geolocator from "~/Utilities/Geolocator";
    import moment from "moment";

    export default {
        props: {
            address: {
                default: function () {
                    return {
                        street: "",
                        city: "",
                        zipCode: ""
                    }
                }
            },
            coords: {
                default: function () {
                    return {
                        latitude: -1,
                        longitude: -1
                    }
                }
            }

        },
        watch: {
            address: function (newValue) {
                if (this.parseAddress(this.currentAddress) === this.parseAddress(newValue))
                    return;
                this.lastInputTimestamp = moment();
                console.log("going to set");
                setTimeout(() => {
                    if (moment().diff(this.lastInputTimestamp) < 499)
                        return;
                    console.log("Setting", newValue);
                    Object.assign(this.currentAddress, newValue);
                    this.setMarkerAtAddress();
                }, 500);

            },
            coords: function (newValue) {
                console.log("coords", newValue);
            }
        },
        data() {
            return {
                currentAddress: {
                    street: this.coords.street,
                    city: this.coords.city,
                    zipCode: this.coords.zipCode
                },
                currentCoords: {
                    lat: this.coords.latitude,
                    lng: this.coords.longitude
                },
                center: {lat: 0, lng: 0},
                mapType: "roadmap",
                zoom: 10,
                requestId: 0,
                lastAddressUpdateTimestamp: 0,
            }
        },
        mounted() {
            if (this.areCoordsSet)
                this.centerAtMarker();
            else if (this.isAddressSet) {
                this.setMarkerAtAddress().then(this.centeringAtMarker());
            } else
                this.setMarkerAtUserLocation().then(this.centeringAtMarker());
        },
        computed: {
            isAddressSet() {
                return this.address.street.length > 0
                    && this.address.zipCode.length > 0
                    && this.address.city.length > 0;
            },
            areCoordsSet() {
                return this.coords.latitude !== -1 && this.coords.longitude !== -1;
            }
        },
        methods: {
            centeringAtMarker() {
                return this.centerAtMarker.bind(this);
            },
            centerAtMarker() {
                this.center.lat = this.currentCoords.lat;
                this.center.lng = this.currentCoords.lng;
            },
            updatePosition(event) {
                this.currentCoords.lat = event.lat();
                this.currentCoords.lng = event.lng();
            },
            updateAddress(event) {
                this.applyGeocode({
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()});
            },
            applyGeocode(latlng) {
                let currentRequestId = ++this.requestId;
                return Geolocator.geocdeToAddress(latlng)
                    .then(({address, coords}) => {
                        if (currentRequestId !== this.requestId)
                            return;

                        this.currentAddress.street = address.street + " " + address.streetNumber;
                        this.currentAddress.city = address.city;
                        this.currentAddress.zipCode = address.postalCode;
                        this.currentCoords.lng = coords.longitude;
                        this.currentCoords.lat = coords.latitude;

                        if (this.parseAddress(this.currentAddress) !== this.parseAddress(this.address)) {
                            this.$emit('address-update', {
                                address: this.currentAddress,
                                coordinates: {
                                    latitude: coords.lat,
                                    longitude: coords.lng
                                }
                            });
                        }

//                        console.log("ok:", address);
                    }).catch((err) => {
                    // invalid geocode, shouldn't happen
                });
            },
            setMarkerAt(x, y, z = 12) {
                this.currentCoords.lat = x;
                this.currentCoords.lng = y;

//                = {lat: x, lng: y};
                // this.zoom = z;
            },
            setMarkerAtAddress() {
                Geolocator.addressToGeocode(this.parseAddress(this.address))
                    .then(({coords}) =>{
                    this.applyGeocode({
                        lat:coords.latitude,
                        lng: coords.longitude
                    });
                    this.centerAtMarker();
                }).catch(()=> {}); // invalid address, it's ok.
            },
            parseAddress(address) {
              return address.street + ", " + address.city + ", " + address.zipCode;
            },
            setMarkerAtUserLocation() {
                return this.findHomeLocationViaBrowser()
                    .catch(this.findHomeLocationViaApi.bind(this))
                    .then((position) => this.setMarkerAt(position.coords.latitude, position.coords.longitude, 12))
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
                let ufoCrashSite = {lat: 31.254306, lng: -24.258472};
                this.setMarkerAt(ufoCrashSite.lat, ufoCrashSite.lng);
                this.center = ufoCrashSite;
                this.zoom = 9;
                this.mapType = "satellite";
            }
        },
        components: {},
    }
</script>

<style scoped>

    .debug {
        position: absolute;
        z-index: 1001;
        background: rgba(200, 200, 200, 0.6);
    }

    .map-wrapper {
        margin: 0 !important;
        width: 100%;
    }
</style>
