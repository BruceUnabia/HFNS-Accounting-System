import axios from 'axios';

// Create an Axios instance with a base URL
const instance = axios.create({
  baseURL: 'http://localhost:8000', // Replace with your backend URL
});

export default instance;
