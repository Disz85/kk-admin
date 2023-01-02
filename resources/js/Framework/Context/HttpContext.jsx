import React from 'react';
import PropTypes from 'prop-types';

export const HttpContext = React.createContext({ http: null });

export const HttpProvider = ({ http, children }) => {
    return (
        <HttpContext.Provider value={{ http }}>{children}</HttpContext.Provider>
    );
};

HttpProvider.propTypes = {
    /**
     * Type of http
     */
    http: PropTypes.object,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]),
};
