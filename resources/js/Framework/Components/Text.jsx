import React from 'react';

const Text = ({ entity, name }) => (
    <p className={ 'mb-0' }>{ entity[name] || null }</p>
);

export default Text;
