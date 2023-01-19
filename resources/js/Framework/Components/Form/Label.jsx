import React from 'react';
import PropTypes from 'prop-types';

const Label = ({ className, to, text, children }) => {
    return (
        <label className={ className } htmlFor={to}>
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
