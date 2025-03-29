import './bootstrap';
import axios from 'axios';

// Make Axios available globally
window.axios = axios;

// Optional: Set default headers
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';