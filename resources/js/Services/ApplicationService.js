import axios from 'axios';

import { queryParams } from '../Helpers/url';

axios.defaults.withCredentials = true;

const ApplicationService = {
    list: async (resource, page, size, params = {}) => {
        const response = await axios.get(
            queryParams(`/admin/${resource}`, { ...params, page, size }),
        );
        return response.data;
    },

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
