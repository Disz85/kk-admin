// Immutable

// Shallow merge
/* eslint-disable import/prefer-default-export */
export const update = (change, setState) =>
    setState((old) => ({ ...old, ...change }));
/* eslint-disable import/prefer-default-export */
