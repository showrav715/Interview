
import axios from 'axios'


const myaxios = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/',
});

myaxios.interceptors.request.use(
    (config) => {
        // Get the token from local storage
        const token = JSON.parse(localStorage.getItem('token'));

        // If the token is available, add it to the Authorization header
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        // Handle any request errors here
        return Promise.reject(error);
    }
);
export default myaxios;

