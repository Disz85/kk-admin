export const permitted = (resources, hasPermission) =>
    resources.filter(
        (resource) =>
            !resource.requiresPermission ||
            hasPermission(`manage-${resource.name}`),
    );
export const listable = (resources) =>
    resources.filter((resource) => resource.listable);
