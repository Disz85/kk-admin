import React from 'react';
import PropTypes from 'prop-types';

const Panel = ({ title, children, ...props }) => {
    return (
        <div>
            <div>{title && <h2>{title}</h2>}</div>
            <div>
                {React.Children.map(children, (child) =>
                    React.cloneElement(child, props),
                )}
            </div>
        </div>
    );
};

export default Panel;

Panel.propTypes = {
    /**
     * entity of title
     */
    title: PropTypes.string.isRequired,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
