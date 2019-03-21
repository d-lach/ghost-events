import axios from 'axios';

let serverConnection = () => {
    let connection = axios.create({
        baseURL: process.env.MIX_APP_URL + '/api/',
        withCredentials: true,
    });

    connection.interceptors.response.use(res => {
        return {data: res.data, success: true};
    }, verboseErrorHandler);

    return connection;
};

export default serverConnection;

let verboseErrorHandler = (error) => {
    let {response: {status, data}} = error;
    console.log({error, status, data});

    return {data, status, success: false};
};
