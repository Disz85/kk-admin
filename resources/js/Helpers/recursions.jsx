import React from 'react';
import Iterator from '../Framework/Components/Iterator';
import defaultValue from '../Framework/Components/Form/DeaultValue';

export const recursiveMap = (children, fn) => {
    return React.Children.map(children, (child) => {
        let currentChild = child;

        if (!React.isValidElement(currentChild)) {
            return currentChild;
        }

        if (currentChild.props.children) {
            currentChild = React.cloneElement(child, {
                children: recursiveMap(child.props.children, fn),
            });
        }

        return fn(currentChild);
    });
};

export function recursiveEach(children, fn) {
    return React.Children.map(children, (child) => {
        let currentChild = child;

        if (!React.isValidElement(currentChild)) {
            return currentChild;
        }

        const cont = fn(currentChild);
        if (cont !== false) {
            if (currentChild.props.children && currentChild.type !== Iterator) {
                currentChild = React.cloneElement(child, {
                    children: recursiveEach(child.props.children, fn),
                });
            }
        }

        return currentChild;
    });
}

export function createNewEntityFromChildren(children) {
    const data = {};

    recursiveEach(children, (child) => {
        if ([Iterator].indexOf(child.type) !== -1) {
            return false;
        }

        if (!child.props.name) {
            return false;
        }

        data[child.props.name] = defaultValue(child);

        return data[child.props.name];
    });

    return data;
}
