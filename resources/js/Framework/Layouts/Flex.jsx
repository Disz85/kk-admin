import React from 'react';
import PropTypes from 'prop-types';

const capitalizeFirstLetter = (str) =>
    str.charAt(0).toUpperCase() + str.slice(1);

const Flex = ({
    direction = 'row',
    wraps = 'noWrap',
    justifyContent = 'Start',
    alignItems = 'Strech',
    alignContent = 'Strech',
    children,
    classNames = '',
}) => {
    return (
        <div
            className={`l-flex -${direction} -${wraps} -justify${capitalizeFirstLetter(
                justifyContent,
            )} -alignItems${capitalizeFirstLetter(
                alignItems,
            )} -alignContent${capitalizeFirstLetter(
                alignContent,
            )} ${classNames}`}
        >
            {children}
        </div>
    );
};

export const FlexChild = ({ children, alignSelf = 'Auto', order = false }) => {
    return (
        <div
            className={`l-flex__child -alignSelf${capitalizeFirstLetter(
                alignSelf,
            )} ${order ? `-order-${order}` : ''}`}
        >
            {children}
        </div>
    );
};

Flex.propTypes = {
    /**
     * Type of direction
     */
    direction: PropTypes.string,
    /**
     * Type of wraps
     */
    wraps: PropTypes.string,
    /**
     * Type of justifyContent
     */
    justifyContent: PropTypes.string,
    /**
     * Type of alignItems
     */
    alignItems: PropTypes.string,
    /**
     * Type of alignContent
     */
    alignContent: PropTypes.string,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
    /**
     * Type of classNames
     */
    classNames: PropTypes.string,
};

Flex.defaultProps = {
    direction: 'row',
    wraps: 'noWrap',
    justifyContent: 'Start',
    alignItems: 'Strech',
    alignContent: 'Strech',
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
    /**
     * Type of alignSelf
     */
    alignSelf: PropTypes.string.isRequired,
    /**
     * Type of order
     */
    order: PropTypes.bool.isRequired,
};

export default Flex;
