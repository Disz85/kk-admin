import React from 'react';

const Error = ({ message }) => !message ? null : (
    <div className={ 'm-form__feedback -invalid' }>
        <i className="fas fa-exclamation-triangle mr-3"></i>{ message }
    </div>
);

export default Error;
