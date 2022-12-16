import React from 'react';
import PropTypes from 'prop-types';

const Aside = ({ children }) => {
    return <aside>{children}</aside>;
};

export default Aside;

Aside.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
