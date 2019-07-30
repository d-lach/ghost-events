import Api from './Api';

export default {

    invite(eventId, userEmail) {
        return Api().post('events/' + eventId + "/invite", {email:userEmail})
    }
};
