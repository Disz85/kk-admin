import React from 'react';
import PropTypes from 'prop-types';
import style from '../../../../scss/components/form.module.scss';

const Error = ({ message }) => {
    return (!message ? null : <div className={ style.error }>{message}</div>);
}

export default Error;

Error.propTypes = {
    /**
     * Type of message
     */
    message: PropTypes.string.isRequired,
};
