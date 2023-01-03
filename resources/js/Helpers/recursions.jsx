import React from "react";

export const recursiveMap = (children, fn) => {
    return React.Children.map(children, child => {
        if (!React.isValidElement(child)) {
            return child;
        }

        if (child.props.children) {
            child = React.cloneElement(child, {
                children: recursiveMap(child.props.children, fn)
            });
        }

        return fn(child);
    });
}

export function recursiveEach(children, fn) {
    return React.Children.map(children, child => {
        if (!React.isValidElement(child)) {
            return child;
        }

        const cont = fn(child);
        if (cont !== false) {
            if (child.props.children && child.type !== Iterator) {
                child = React.cloneElement(child, {
                    children: recursiveEach(child.props.children, fn)
                });
            }
        }

        return child;
    });
}

export function createNewEntityFromChildren(children) {

    const data = {};

    recursiveEach(children, child => {
        if ([ Iterator ].indexOf(child.type) !== -1) {
            return false;
        }

        if (!child.props.name) {
            return;
        }

        data[child.props.name] = defaultValue(child);
    });

    return data;
}
