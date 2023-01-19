import React from 'react';

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

export default Flex;
