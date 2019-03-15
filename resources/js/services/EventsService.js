import Api from './Api';

export default {

    save(event) {
        if (event.id)
            return Api().put('events/' + event.id + "/update", event);

        return Api().post('events', event);
    },

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
