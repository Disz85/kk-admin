import React from 'react';
import PropTypes from 'prop-types';

import { Link } from 'react-router-dom';

const Arrow = ({ resource, to, enabled, direction }) => {
    if (enabled) {
        return (
            <Link to={`/${resource}/page/${to}${window.location.search}`}>
                {direction ?? 'arrow'}
            </Link>
        );
    }

    return <span>arrow</span>;
};

export default Arrow;

Arrow.propTypes = {
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of to
     */
    to: PropTypes.number.isRequired,
    /**
     * Type of enabled
     */
    enabled: PropTypes.bool.isRequired,
    /**
     * Type of direction
     */
    direction: PropTypes.string.isRequired,
};
