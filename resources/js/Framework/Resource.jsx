/* eslint-disable no-unused-vars */
import React from 'react';
import PropTypes from 'prop-types';

const Resource = ({
    name,
    list = undefined,
    form = undefined,
    routes = [],
    requiresPermission = true,
}) => null;

export default Resource;
/* eslint-disable no-unused-vars */

Resource.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of list
     */
    list: PropTypes.func,
    /**
     * Type of form
     */
    form: PropTypes.func,
    /**
     * Type of routes
     */
    routes: PropTypes.array,
    /**
     * Type of requiresPermission
     */
    requiresPermission: PropTypes.bool,
};

Resource.defaultProps = {
    routes: [],
    requiresPermission: true,
    form: undefined,
    list: undefined,
};
