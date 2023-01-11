import React from 'react';
import PropTypes from 'prop-types';

import Item from './List/Item';
import List from './List/List';

const Iterator = ({
    collection,
    children,
    path,
    optimized = true,
    ordered = false,
    ...props
}) => {
    return (
        collection.length && (
            <List ordered={ordered}>
                {collection.map((item, index) => (
                    <Item
                        key={item.key || index}
                        index={index}
                        item={item}
                        elements={children}
                        optimized={optimized}
                        path={`${path}.${index}`}
                        {...props}
                    />
                ))}
            </List>
        )
    );
};

export default Iterator;

Iterator.propTypes = {
    /**
     * Type of collection
     */
    collection: PropTypes.object.isRequired,
    /**
     * Type of path
     */
    path: PropTypes.string.isRequired,
    /**
     * Type of optimized
     */
    optimized: PropTypes.bool.isRequired,
    /**
     * Type of ordered
     */
    ordered: PropTypes.bool.isRequired,
    /**
     * Type of resources
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
