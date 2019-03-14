import Api from './Api';

export default {

    getEventsFromSoonest(page = 1) {
        return Api().get('events/all?page=', page);

    },

    getEventsInProximity(geoCoords, range = 5) {
        throw new Error("not implemented");
        //return Api().post('closed/cart/remove/' + id);
    }
};
