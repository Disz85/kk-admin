import axios from 'axios';

/**
 *
 * @param {File} image
 * @param {string} resource
 * @returns {FormData}
 */
const createForm = (image, resource) => {
    const form = new FormData();

    form.append('resource', resource);
    form.append('media', image);

    return form;
};

/**
 *
 * @param {FileList} fileList
 * @param {string} resource
 * @returns {FormData}
 */
const createMultipleUploadForm = (fileList, resource) => {
    const form = new FormData();

    form.append('resource', resource);
    Array.from(fileList).forEach((file) => form.append('media[]', file));

    return form;
};

axios.defaults.withCredentials = true;

export default {
    /**
     *
     * @param {File} image
     * @param {string} resource
     * @returns {Promise<AxiosResponse<any>>}
     */
    upload: (image, resource) => {
        return axios
            .post('/admin/media-library/upload', createForm(image, resource))
            .then((r) => r.data);
    },

    /**
     *
     * @param {FileList} fileList
     * @param {string} resource
     * @returns {Promise<AxiosResponse<any>>}
     */
    uploadMultiple: (fileList, resource) => {
        return axios
            .post(
                '/admin/media-library/upload-multiple',
                createMultipleUploadForm(fileList, resource),
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                },
            )
            .then((r) => r.data);
    },

    delete: (id) => {
        return axios.delete('/admin/media-library/'.concat(id));
    },

    deleteMultiple: (ids) => {
        return axios.post('/admin/media-library/delete-multiple', ids);
    },
};
