import geolocator from 'geolocator'

geolocator.config({
    language: "en",
    google: {
        // version: "3",
        key: process.env.MIX_GOOGLE_API_KEY
    }
});

export default class Geolocator {
    static locate(latlng = null) {
        return new Promise((resolve, reject) => {
            let options = {
                enableHighAccuracy: true,
                timeout: 1000,
                maximumWait: 2000,     // max wait time for desired accuracy
                maximumAge: 0,          // disable cache
                desiredAccuracy: 30,    // meters
                fallbackToIP: true,     // fallback to IP if Geolocation fails or rejected
                addressLookup: true,    // requires Google API key if true
                timezone: true,         // requires Google API key if true
            };

            geolocator.locate(options, function (err, location) {
                if (err) {
                    reject(err);
                    return;
                }
                resolve(location);
            });
        })
    }

    static addressToGeocode(address) {
        return new Promise((resolve, reject) => {
            geolocator.geocode(address, function (err, location) {
                if (err) {
                    reject(err);
                    return;
                }
                resolve(location);
            });
        })
    }

    static geocdeToAddress(latlng) {
        return new Promise((resolve, reject) => {
            geolocator.reverseGeocode({
                latitude: latlng.lat,
                longitude: latlng.lng
            }, function (err, location) {
                if (err) {
                    reject(err);
                    return;
                }
                resolve(location);
            });
        })
    }
}