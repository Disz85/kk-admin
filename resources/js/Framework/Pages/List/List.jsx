import React, { useState, useEffect, useContext } from 'react';
import useDebounce from '../../../Hooks/useDebounce';
// CONTEXTS
import ApplicationContext from '../../Context/ApplicationContext';
// HELPERS
import { parse, stringify } from 'qs';
import _ from 'lodash';
import { update } from '../../../Helpers/objectOperations';
import { getFields } from '../../../Helpers/getters';

// COMPONENTS
import Table from '../../Components/Table';

const List = ({ resource, service, search: SearchForm = null, children }) => {
    // CONTEXTS
    const setPageInfo = useContext(ApplicationContext);

    // STATES
    const [isLoading, setIsLoading] = useState(false);
    const [fields] = useState(getFields(children));
    const [list, setList] = useState({
        last: 0,
        total: 0,
        entities: [],
        params: parse(window.location.search.replace('?', '')),
    });

    // EVENTS
    const updateSearch = useDebounce(500, (newParams) => {
        if (!_.isEqual(list.params, newParams)) {
            window.history.replaceState(
                null,
                '',
                `/${resource}?${stringify(newParams)}`,
            );

            update({ current: 1, params: newParams }, setList);
        }
    });

    const paginate = () => {
        setIsLoading(true);
        service
            .list(resource, list.current, 25, list.params)
            .then(({ data: entities, meta }) => {
                update({
                    current: meta.current_page,
                    last: meta.last_page,
                    total: meta.total,
                    entities,
                }, setList);
            })
            .finally(() => {
                setIsLoading(false);
            });
    };

    // SIDE EFFECTS
    useEffect(() => {
        setPageInfo({ title: 'Lista', icon: 'icon' });
    }, []);

    useEffect(() => {
        paginate();
    }, [list.current, list.params]);

    return (
        <React.Fragment>
            {isLoading && <p>Loading...</p>}
            {!isLoading && list.entities.length && (
                <Table
                    entities={list.entities}
                    fields={fields}
                    data={{ resource, service }}
                />
            )}
        </React.Fragment>
    );
};

export default List;
