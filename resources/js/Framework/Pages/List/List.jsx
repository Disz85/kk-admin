import React, { useState, useEffect, useContext } from 'react';
import { useParams } from 'react-router-dom';
import PropTypes from 'prop-types';

// TRANSLATION
import { useTranslation } from 'react-i18next';

// HELPERS
import { parse, stringify } from 'qs';
import _ from 'lodash';
import { update } from '../../../Helpers/objectOperations';
import getFields from '../../../Helpers/getters';

// CONFIG
import navigationIcons from '../../../config/navigationIcons';

// HOOKS
import useDebounce from '../../../Hooks/useDebounce';
import useUpdateEffect from '../../../Hooks/useUpdateEffect';

// CONTEXTS
import ApplicationContext from '../../Context/ApplicationContext';

// COMPONENTS
import Table from '../../Components/Table';
import Create from '../../Components/Buttons/Create';
import Paginator from '../../Components/Paginator/Paginator';
import Modal from '../../Components/Modal';
import Button from '../../Components/Buttons/Button';

// STYLES
import style from '../../../../scss/components/list.module.scss';

const List = ({
    resource,
    service,
    search: SearchForm = null,
    readonly = false,
    children,
}) => {
    // CONTEXTS
    const setPageInfo = useContext(ApplicationContext);
    const { t } = useTranslation();

    // STATES
    const [isLoading, setIsLoading] = useState(false);
    const [marked, setMarked] = useState(null);
    const [fields] = useState(getFields(children));
    const { page = 1 } = useParams();
    const [list, setList] = useState({
        current: Number(page),
        last: 0,
        total: 0,
        entities: [],
        params: parse(window.location.search.replace('?', '')),
    });
    const [deleteError, setDeleteError] = useState(false);

    let url = resource;
    if (window.location.pathname.includes('categories')) {
        const type = window.location.pathname.split('/')[1];

        list.params.type = type.replace('categories-', '');
        url = 'categories';
    }

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
            .list(url, list.current, 25, list.params)
            .then(({ data: entities, meta }) => {
                update(
                    {
                        current: meta.current_page,
                        last: meta.last_page,
                        total: meta.total,
                        entities,
                    },
                    setList,
                );
            })
            .finally(() => {
                setIsLoading(false);
            });
    };

    // DELETE
    const markEntityForDeletion = (entity) => setMarked(entity);
    const clearMark = () => {
        setMarked(null);
        setDeleteError(false);
    };

    const removeEntity = (entity) =>
        service
            .remove(url, entity.id)
            .then(() => paginate())
            .then(() => setMarked(null))
            .catch((error) => {
                if (error.response) {
                    setDeleteError(error.response.data.message);
                }
            });

    // SIDE EFFECTS
    useEffect(() => {
        setPageInfo({
            title: t(`${resource}.${resource}`),
            icon: navigationIcons[resource],
        });
    }, []);

    useEffect(() => {
        paginate();
    }, [list.current, list.params]);

    useUpdateEffect(() => {
        update({ current: Number(page) }, setList);
    }, [page]);

    return (
        <>
            {SearchForm && (
                <SearchForm
                    resource={resource}
                    entity={list.params}
                    service={service}
                    onChange={updateSearch}
                />
            )}

            {isLoading && <p>Loading...</p>}
            {!isLoading && (
                <Table
                    entities={list.entities}
                    fields={fields}
                    data={{ resource, service }}
                    remove={markEntityForDeletion}
                />
            )}
            <Paginator resource={resource} pagination={list} />
            {!readonly && <Create resource={resource} />}

            {!readonly && (
                <Modal
                    contentLabel="Törlés megerősítése"
                    isOpen={!!marked}
                    onRequestClose={clearMark}
                >
                    {marked && (
                        <p>
                            Biztosan törlöd a következőt:{' '}
                            <strong>
                                {marked.name ||
                                    marked.title ||
                                    marked.search_term}
                            </strong>
                            ?
                        </p>
                    )}
                    {!deleteError && (
                        <Button
                            name="delete"
                            click={() => removeEntity(marked)}
                        >
                            Törlés
                        </Button>
                    )}
                    {deleteError && (
                        <div className={style.errorMessage}>
                            {t(`application.${deleteError}`, {
                                resource: t(`application.${resource}`),
                            })}
                        </div>
                    )}
                </Modal>
            )}
        </>
    );
};

export default List;

List.propTypes = {
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of search
     */
    search: PropTypes.func,
    /**
     * Type of readonly
     */
    readonly: PropTypes.bool.isRequired,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

List.defaultProps = {
    search: null,
};
