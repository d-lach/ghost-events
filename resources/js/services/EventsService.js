import Api from './Api';

export default {

    getAll() {
        return Api().get('events/all');
    },

    getEventsFromSoonest(page = 1) {
        return Api().get('events/all/', page);
    },

    getEventsInProximity(geoCoords, range = 5) {
        throw new Error("not implemented");
    }
};
