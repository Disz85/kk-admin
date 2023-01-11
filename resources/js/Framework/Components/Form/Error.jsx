import React from 'react';
import PropTypes from 'prop-types';

const Error = ({ message }) => (!message ? null : <div>{message}</div>);

export default Error;

Error.propTypes = {
    /**
     * Type of message
     */
    message: PropTypes.string.isRequired,
};
