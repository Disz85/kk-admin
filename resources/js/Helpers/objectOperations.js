// Immutable

// Shallow merge
export const update = (change, setState) => setState((old) => ({ ...old, ...change }));
