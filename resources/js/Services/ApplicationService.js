import axios from 'axios';

axios.defaults.withCredentials = true;

const ApplicationService = {
    xsrf: () => axios.get('/sanctum/csrf-cookie'),

    login: async (token) => {
        const response = await axios.get('/admin/auth/login', {
            headers: { Authorization: `Bearer ${token}` },
        });
        return response.data;
    },

    logout: async () => {
        const response = await axios.get('/admin/auth/logout');
        return response.data;
    },
};

export default ApplicationService;
