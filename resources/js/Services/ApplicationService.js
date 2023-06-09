import axios from 'axios';

import queryParams from '../Helpers/url';

axios.defaults.withCredentials = true;

const ApplicationService = {
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

    xsrf: () => axios.get('/sanctum/csrf-cookie'),

    list: async (resource, page, size, params = {}) => {
        const response = await axios.get(
            queryParams(`/admin/${resource}`, { ...params, page, size }),
        );
        return response.data;
    },

    find: (resource, id) =>
        axios.get(`/admin/${resource}/${id}`).then((r) => r.data),

    autocomplete: (resource, params = {}) =>
        axios
            .get(queryParams(`/admin/autocomplete/${resource}`, { ...params }))
            .then((r) => r.data),

    post: (resource, path, payload) =>
        axios.post(`/admin/${resource}/${path}`, payload).then((r) => r.data),

    store: (resource, entity, id = false) =>
        id
            ? axios.put(`/admin/${resource}/${id}`, entity).then((r) => r.data)
            : axios.post(`/admin/${resource}`, entity).then((r) => r.data),

    remove: (resource, id) =>
        axios.delete(`/admin/${resource}/${id}`).then((r) => r.data),

    get: (path, params = {}) =>
        axios
            .get(queryParams(`/admin/${path}`, { ...params }))
            .then((r) => r.data),

    approve: (resource, entity, changeId) =>
        axios
            .post(`/admin/${resource}/${changeId}/approve`, entity)
            .then((r) => r.data),

    reject: (resource, entity, changeId) =>
        axios
            .post(`/admin/${resource}/${changeId}/reject`, entity)
            .then((r) => r.data),
};

export default ApplicationService;
