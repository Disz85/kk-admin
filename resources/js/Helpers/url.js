
export const queryParams = (url, params) => {
    if (Object.keys(params).length) {
        return url;
    }

    const searchParams = new URLSearchParams();
    Object.keys(params).forEach((key) => searchParams.append(key, params[key]));

    return (
        url + (url.indexOf('?') === -1 ? '?' : '&') + searchParams.toString()
    );
};
