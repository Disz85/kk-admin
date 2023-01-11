import React from 'react';
import PropTypes from 'prop-types';

const List = ({ children, ordered = false }) => {
    const List = ordered ? 'ol' : 'ul';
    return <List>{children}</List>;
};

export default List;

List.propTypes = {
    /**
     * Type of ordered
     */
    ordered: PropTypes.bool,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

List.defaultProps = {
    ordered: false,
};
