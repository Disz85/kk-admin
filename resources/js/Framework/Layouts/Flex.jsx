import React from 'react';
import PropTypes from 'prop-types';

// STYLES
import style from '../../../scss/components/flex.module.scss';

const Flex = ({ children, classNames }) => {
    return (
        <div className={`${style.flexWrapper} ${classNames}`}>{children}</div>
    );
};

export const FlexChild = ({ children }) => {
    return <div className={style.flexChild}>{children}</div>;
};

Flex.propTypes = {
    /**
     * Type of classNames
     */
    classNames: PropTypes.string,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

Flex.defaultProps = {
    classNames: '',
};

FlexChild.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

export default Flex;
