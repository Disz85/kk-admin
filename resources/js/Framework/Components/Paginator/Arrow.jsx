import React from 'react';
import PropTypes from 'prop-types';

import { Link } from 'react-router-dom';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faChevronLeft,
    faChevronRight,
} from '@fortawesome/free-solid-svg-icons';

const Arrow = ({ className, resource, to, enabled, isRight = false }) => {
    const icon = isRight ? faChevronRight : faChevronLeft;

    if (enabled) {
        return (
            <Link className={className} to={`/${resource}/page/${to}${window.location.search}`}>
                <FontAwesomeIcon icon={icon} />
            </Link>
        );
    }

    return <div className={className}><FontAwesomeIcon icon={icon} /></div>;
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
    isRight: PropTypes.bool,
};

Arrow.defaultProps = {
    isRight: false,
};
