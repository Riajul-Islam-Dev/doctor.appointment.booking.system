import './bootstrap';
import axios from 'axios';

// Make Axios available globally 
window.axios = axios;

// Set default Axios headers CSRF token
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
