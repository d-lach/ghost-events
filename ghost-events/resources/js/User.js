let _id = null;

export default class User {
    static get id() {
        if (_id === null)
            _id = User._retrieveId();
        return _id;
    }

    static isLoggedIn() {
        return User.id !== -1;
    }

    static _retrieveId() {
        let userElement = document.querySelector("meta[name='user-id']");
        if (userElement)
            return parseInt(userElement.getAttribute('content'));
        return -1;
    }
}