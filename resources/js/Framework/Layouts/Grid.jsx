import React from 'react';
import PropTypes from 'prop-types';

// STYLES
import style from '../../../scss/layouts/grid.module.scss';

const Grid = ({ children, classNames }) => {
    return (
        <div className={`${style.gridWrapper} ${classNames}`}>{children}</div>
    );
};

export const GridLeft = ({ children }) => {
    return (
        <div className={`${style.gridColumn} ${style.gridLeft}`}>
            {children}
        </div>
    );
};

export const GridRight = ({ children }) => {
    return (
        <div className={`${style.gridColumn} ${style.gridRight}`}>
            {children}
        </div>
    );
};

Grid.propTypes = {
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

Grid.defaultProps = {
    classNames: '',
};

GridLeft.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

GridRight.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

export default Grid;
