import React from 'react';

const resourceToCollection = (
    resources,
    { name, list, form, routes = [], requiresPermission = true },
) => {
    if (name && list) {
        resources.push({
            name,
            requiresPermission,
            path: `/${name}`,
            component: list,
            listable: true,
        });
        resources.push({
            name,
            requiresPermission,
            path: `/${name}/page/:page`,
            component: list,
            listable: false,
        });
    }

    if (name && form) {
        resources.push({
            name,
            requiresPermission,
            path: `/${name}/new`,
            component: form,
            listable: false,
        });
        resources.push({
            name,
            requiresPermission,
            path: `/${name}/:id/show`,
            component: form,
            listable: false,
        });
    }

    routes.map(({ path, listable, component }) =>
        resources.push({
            name,
            component,
            listable,
            requiresPermission,
            path: `/${name}${path}`,
        }),
    );
};

const registerResources = (children) => {
    const resources = [];
    React.Children.forEach(children, (child) =>
        resourceToCollection(resources, child.props),
    );
    return resources;
};

export default registerResources;
