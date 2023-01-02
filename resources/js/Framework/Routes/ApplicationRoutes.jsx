import React from 'react';
import PropTypes from 'prop-types';

import { Route } from 'react-router-dom';
import Resource from "../Resource";

const ApplicationRoute = ({
    component: Component,
    resource,
    service,
    ...rest
}) => (
    <Component resource={resource} service={service} />
);

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
    /*Proptypes.shape({
              name: PropTypes.string.isRequired,
              routes: PropTypes.array,
              requiresPermission: PropTypes.bool,
          })*/
    /**
     * Type of service
     */
    service: PropTypes.shape({
        http: PropTypes.object,
        children: PropTypes.oneOfType([
            PropTypes.arrayOf(PropTypes.node),
            PropTypes.node,
        ])
    }).isRequired,
};
