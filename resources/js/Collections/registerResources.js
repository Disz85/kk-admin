import React from 'react';

const resourceToCollection = (
    resources,
    {
        name,
        list,
        form,
        groupParent,
        children,
        routes = [],
        requiresPermission = true,
    },
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

    if (name && groupParent) {
        resources.push({
            name,
            requiresPermission,
            group: name,
            groupParent,
            listable: true,
            children,
        });

        children.map((child) => {
            const {
                name: childName,
                list: childList,
                form: childForm,
                group: childGroup,
                requiresPermission: childRequiresPermission = true,
            } = child.props;

            if (childName && childList) {
                resources.push({
                    name: childName,
                    requiresPermission: childRequiresPermission,
                    path: `/${childName}`,
                    component: childList,
                    listable: true,
                    group: childGroup,
                });
                resources.push({
                    name: childName,
                    requiresPermission: childRequiresPermission,
                    path: `/${childName}/page/:page`,
                    component: childList,
                    listable: false,
                    group: childGroup,
                });
            }

            if (childName && childForm) {
                resources.push({
                    name: childGroup,
                    requiresPermission: childRequiresPermission,
                    path: `/${childGroup}/new`,
                    component: childForm,
                    listable: false,
                    group: childGroup,
                });
                resources.push({
                    name: childGroup,
                    requiresPermission: childRequiresPermission,
                    path: `/${childGroup}/:id/show`,
                    component: childForm,
                    listable: false,
                    group: childGroup,
                });
            }
            return null;
        });
    }

    routes.map(({ path, listable, component }) =>
        resources.push({
            name,
            requiresPermission,
            path: `/${name}${path}`,
            component,
            listable,
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
