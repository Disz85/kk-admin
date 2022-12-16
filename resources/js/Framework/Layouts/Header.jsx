import React from 'react';
import PropTypes from 'prop-types';

const Header = ({ children }) => {
    return <header>{children}</header>;
};

export default Header;

Header.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
