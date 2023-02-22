import React from 'react';
import PropTypes from 'prop-types';

// ICONS
import { faCheck, faTimes } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

const Boolean = ({ name, entity }) => {
    return entity[name] ? (
        <FontAwesomeIcon icon={faCheck} />
    ) : (
        <FontAwesomeIcon icon={faTimes} />
    );
};

export default Boolean;

Boolean.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
};
