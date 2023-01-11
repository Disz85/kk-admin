import React from 'react';
import PropTypes from 'prop-types';

import { Link } from 'react-router-dom';

const Edit = ({ resource, entity, name }) => (
    <Link to={`/${resource}/${entity.id}/show`}>{entity[name]}</Link>
);

export default Edit;

Edit.propTypes = {
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.shape({
        id: PropTypes.number,
    }).isRequired,
    /**
     * Type of entity
     */
    name: PropTypes.string.isRequired,
};
