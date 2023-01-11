import React from 'react';
import PropTypes from 'prop-types';

const ApplicationRoute = ({
    component: Component,
    resource,
    service,
    ...rest
}) => <Component resource={resource} service={service} {...rest} />;

export default ApplicationRoute;

ApplicationRoute.propTypes = {
    /**
     * Type of component
     */
    component: PropTypes.elementType.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.shape({
        http: PropTypes.object,
        children: PropTypes.oneOfType([
            PropTypes.arrayOf(PropTypes.node),
            PropTypes.node,
        ]),
    }).isRequired,
};
