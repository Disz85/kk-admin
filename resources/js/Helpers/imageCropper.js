import React from 'react';

export const cropParams = (url, { width, height, x, y }) => {
    let params = {};
    if (width + height + x + y > 0) {
        params = { crop: [width, height, x, y].join(',') };
    }

    return queryParams(url, params);
};

const sanitizeCropValue = (value) => Math.max(parseInt(value, 10) || 0, 0);

export const sanitizeCropValues = ({ width, height, x, y }) => ({
    width: sanitizeCropValue(width),
    height: sanitizeCropValue(height),
    x: sanitizeCropValue(x),
    y: sanitizeCropValue(y),
});

export const queryParams = (url, params) => {
    if (Object.keys(params).length === 0) {
        return url;
    }

    const searchParams = new URLSearchParams();
    Object.keys(params).forEach((key) => searchParams.append(key, params[key]));

    return (
        url + (url.indexOf('?') === -1 ? '?' : '&') + searchParams.toString()
    );
};
