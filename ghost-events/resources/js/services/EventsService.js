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
    },

    getUserEventsIds() {
        return Api().get('events/all-mine-ids');
    },

    leave(eventId) {
        return Api().post('events/' + eventId + "/leave");
    },

    join(eventId) {
        return Api().post('events/' + eventId + "/join")
    },

    addGuest(eventId, userId) {
        return Api().post('events/' + eventId + "/guests/add", {userId})

    },

    removeGuest(eventId, userId) {
        return Api().post('events/' + eventId + "/guests/remove", {userId})
    }
};
