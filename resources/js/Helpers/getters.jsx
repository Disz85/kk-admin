import React from "react";

export const getFields = children => React.Children.map(children, ({ type, props : { ...rest } }) => ({
    component : type,
    ...rest
}));
