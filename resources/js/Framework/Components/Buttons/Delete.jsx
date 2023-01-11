import React from 'react';
import PropTypes from 'prop-types';

import Button from './Button';

const Delete = ({ entity, remove, ...props }) => (
    <Button click={() => remove(entity)} {...props} />
);

export default Delete;

Delete.propTypes = {
    /**
     * entity of remove
     */
    remove: PropTypes.func.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of props
     */
    props: PropTypes.array,
};

Delete.defaultProps = {
    props: [],
};
