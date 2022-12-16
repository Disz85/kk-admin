import React from 'react';
import PropTypes from 'prop-types';

import { Route } from 'react-router-dom';

const ApplicationRoute = ({
    component: Component,
    resource,
    service,
    ...rest
}) => (
    <Route
        {...rest}
        render={(props) => (
            <Component resource={resource} service={service} {...props} />
        )}
    />
);

export default ApplicationRoute;

ApplicationRoute.propTypes = {
    /**
     * Type of component
     */
    component: PropTypes.bool.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.shape.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.shape.isRequired,
};
