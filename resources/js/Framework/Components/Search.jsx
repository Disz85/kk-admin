import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
// HELPERS
import { stringify, parse } from 'qs';
import {
    createNewEntityFromChildren,
    recursiveMap,
} from '../../Helpers/recursions';
// COMPONENTS
import Button from './Buttons/Button';
import Panel from './Panel';

const Search = ({ entity, onChange, children, ...props }) => {
    // IMMUTABLE STATE
    const [fields] = useState(createNewEntityFromChildren(children));

    // MUTABLE STATE
    const [params, setParams] = useState({
        ...fields,
        ...parse(window.location.search.replace('?', '')),
    });

    // CALLBACKS
    const update = (change) => {
        setParams((params) => ({ ...params, ...change }));
    };

    // SIDE EFFECTS
    // On params change update the query string
    useEffect(() => {
        const query = stringify(params, {
            filter: (prefix, value) => {
                if ((value && value.id) || null) {
                    return value.id;
                }

                if (typeof value === 'boolean' && value) {
                    return 1;
                }

                if (!value) {
                    return null;
                }

                return value;
            },
        });

        window.history.replaceState({ at: Date.now() }, '', `?${query}`);
    }, [params]);

    // On query params change notify parent
    useEffect(() => {
        onChange({
            ...fields,
            ...parse(window.location.search.replace('?', '')),
        });
    }, [params]);

    return (
        <Panel title="Szűrő">
            <form>
                {recursiveMap(children, (child) =>
                    React.cloneElement(child, {
                        ...props,
                        errors: {},
                        entity: params,
                        onChange: update,
                    }),
                )}

                <Button
                    name="reset"
                    click={() => setParams(fields)}
                    unlabeled
                />
            </form>
        </Panel>
    );
};

export default Search;

Search.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
