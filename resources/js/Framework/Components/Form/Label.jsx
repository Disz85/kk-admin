import React from 'react';
import PropTypes from 'prop-types';

const Label = ({ to, text, children }) => {
    return (
        <label htmlFor={to}>
            {text}
            {children}
        </label>
    );
};

export default Label;

Label.propTypes = {
    /**
     * Type of to
     */
    to: PropTypes.string.isRequired,
    /**
     * Type of text
     */
    text: PropTypes.string.isRequired,
    /**
     * Type of resources
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
