import React from 'react';
import PropTypes from 'prop-types';

const List = ({ children, ordered = false, ...props }) => {
    const List = ordered ? 'ol' : 'ul';
    return <List {...props}>{children}</List>;
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
