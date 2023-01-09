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
import Create from '../../Components/Create';
import Paginator from './Paginator';
import { useTranslation } from 'react-i18next';
import Modal from '../../Components/Modal';
import { Button } from '../../Components/Button';

const List = ({ resource, service, search: SearchForm = null, readonly = false, children }) => {
    // CONTEXTS
    const setPageInfo = useContext(ApplicationContext);
    const { t } = useTranslation();

    // STATES
    const [isLoading, setIsLoading] = useState(false);
    const [marked, setMarked] = useState(null);
    const [fields] = useState(getFields(children));
    const [list, setList] = useState({
        current: 0,
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

            update({ current: 1, params: newParams}, setList);
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

    //DELETE
    const markEntityForDeletion = entity => setMarked(entity);
    const clearMark = () => setMarked(null);

    const removeEntity = entity => service.remove(resource, entity.id).then(() => paginate()).then(() => setMarked(null));

    // SIDE EFFECTS
    useEffect(() => {
        setPageInfo({ title:  t(`application.list`, { resource : t(`${resource}.resource`) }), icon: 'icon' });
    }, []);

    useEffect(() => {
        paginate();
    }, [list.current, list.params]);

    return (
        <React.Fragment>
            { SearchForm && <SearchForm resource={ resource } entity={ list.params } service={ service } onChange={ updateSearch }/> }

            {isLoading && <p>Loading...</p>}
            {!isLoading && list.entities.length && (
                <Table
                    entities={list.entities}
                    fields={fields}
                    data={{ resource, service }}
                    remove={ markEntityForDeletion }
                />
            )}
            <div>
                <Paginator resource={ resource } pagination={ list }/>
            </div>
            {!readonly && (<Create resource={resource} />)}

            { !readonly && (
                <Modal contentLabel="Törlés megerősítése" isOpen={ !!marked } onRequestClose={ clearMark }>
                    { marked && <p>Biztosan törlöd a következőt: <strong>"{ marked.name || marked.title || marked.search_term }"</strong>?</p> }
                    <Button name={ 'delete' } className={ 'm-auto' } click={ () => removeEntity(marked) }>Törlés</Button>
                </Modal>
            ) }
        </React.Fragment>
    );
};

export default List;
