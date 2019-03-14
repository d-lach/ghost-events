import axios from 'axios';

let serverConnection = () => {
    let connection = axios.create({
        baseURL: process.env.MIX_APP_URL + '/api/',
        withCredentials: true,
    });

    connection.interceptors.response.use(res => {
        return res.data;
    }, verboseErrorHandler);

    return connection;
};

export default serverConnection;

let verboseErrorHandler = (error) => {
    let {response: {status, data: details}} = error;
    console.log({error, status, details});

    return {error, success: false};
};
