import React from 'react';

const getFields = (children) =>
    React.Children.map(children, ({ type, props: { ...rest } }) => ({
        component: type,
        ...rest,
    }));

export default getFields;
