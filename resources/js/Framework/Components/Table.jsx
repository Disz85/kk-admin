import React from 'react';
import PropTypes from 'prop-types';
// TRANSLATION
import { useTranslation } from 'react-i18next';
// STYLE
import style from '../../../scss/components/table.module.scss';

const Table = ({ fields, entities, data, remove }) => {
    const { resource, service } = data;

    const { t } = useTranslation();

    return (
        <div className={style.tableWrapper}>
            <table className={style.inner}>
                <thead className={style.header}>
                    <tr className={style.row}>
                        {fields.map(({ name }) => (
                            <th key={name} className={style.title}>
                                {t(`${resource}.${name}`)}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody className={style.body}>
                    {entities &&
                        entities.map((entity) => (
                            <tr key={entity.id} className={style.row}>
                                {fields.map(
                                    ({ component: Component, ...rest }) => (
                                        <td
                                            className={style.column}
                                            key={rest.name + entity.id}
                                        >
                                            <Component
                                                service={service}
                                                resource={resource}
                                                entity={entity}
                                                remove={remove}
                                                {...rest}
                                            />
                                        </td>
                                    ),
                                )}
                            </tr>
                        ))}
                </tbody>
            </table>
        </div>
    );
};

export default Table;

Table.propTypes = {
    /**
     * Type of fields
     */
    fields: PropTypes.array.isRequired,
    /**
     * Type of entities
     */
    entities: PropTypes.array.isRequired,
    /**
     * Type of data
     */
    data: PropTypes.shape({
        resource: PropTypes.string.isRequired,
        service: PropTypes.object.isRequired,
    }).isRequired,
    /**
     * Type of remove
     */
    remove: PropTypes.func.isRequired,
};
