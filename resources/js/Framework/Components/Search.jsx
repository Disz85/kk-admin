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

// STYLE
import style from '../../../scss/components/search.module.scss';
import { faFilter, faChevronUp, faChevronDown, faTrash } from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

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

    // SEARCH
    const [ activeSearch, setSearch ] = useState(false);
    const [ height, setHeight ] = useState({ height: 0, overflow: 'hidden' });

    const toggleAccordion = () => setSearch(activeSearch => !activeSearch);
    useEffect(() => {
        setHeight(activeSearch ? { height: 0, overflow: 'hidden' } : { height : 100 + '%' , overflow : 'visible'});
    }, [activeSearch]);

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
        <div className={style.searchWrapper}>
            <div className={style.searchHeader}>
                <div className={style.searchTitle}>
                    <FontAwesomeIcon icon={faFilter} />
                    <h2>Szűrő</h2>
                </div>
                <Button icon={activeSearch ? faChevronDown : faChevronUp} name="search-open" click={toggleAccordion} unlabeled={true}></Button>
            </div>

            <form className={style.searchForm} style={ height }>
                {recursiveMap(children, (child) =>
                    React.cloneElement(child, {
                        ...props,
                        errors: {},
                        entity: params,
                        onChange: update,
                    }),
                )}

                <div className={style.searchResetWrapper}>
                    <Button
                        className={style.searchReset}
                        name="reset"
                        icon={faTrash}
                        click={() => setParams(fields)}
                        unlabeled
                    />
                </div>
            </form>
        </div>
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
