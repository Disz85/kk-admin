import React from 'react';
import PropTypes from 'prop-types';

const optimize = (prev, next) =>
    next.optimized && prev.item === next.item && prev.errors === next.errors;

const childrenOnly = (children) =>
    React.Children.toArray(children).filter((o) => !!o);

const ListItem = ({ item, elements, onChange, remove, ...props }) => {
    return (
        <li key={item.key}>
            {childrenOnly(elements).map((child) =>
                React.cloneElement(child, {
                    ...props,
                    key: child.props.name,
                    entity: item,
                    onChange: onChange(item),
                    remove: remove(item),
                }),
            )}
        </li>
    );
};

const Item = React.memo(ListItem, optimize);

export default Item;

ListItem.propTypes = {
    /**
     * Type of item
     */
    item: PropTypes.shape({
        key: PropTypes.string.isRequired,
    }).isRequired,
    /**
     * Type of elements
     */
    elements: PropTypes.array.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of remove
     */
    remove: PropTypes.func.isRequired,
};
