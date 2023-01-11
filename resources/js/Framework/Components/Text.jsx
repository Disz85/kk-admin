import React from 'react';
import PropTypes from 'prop-types';

const Text = ({ entity, name }) => {
    return entity && <p>{entity?.[name]}</p>;
};

export default Text;

Text.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
};
