import React from 'react';
import style from '../../../scss/components/table.module.scss';

const Table = ({ fields, entities, data }) => {
    return (
        <div className={style.tableWrapper}>
            <table className={style.inner}>
                <thead className={style.header}>
                    <tr className={style.row}>
                        {fields.map(({ name }) => (
                            <th className={style.title}>{name}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {entities.map((entity) => (
                        <tr key={entity.id} className={style.row}>
                            {fields.map(({ component: Component, ...rest }) => (
                                <td
                                    className={style.column}
                                    key={rest.name + entity.id}
                                >
                                    <Component
                                        service={data.service}
                                        resource={data.resource}
                                        entity={entity}
                                        {...rest}
                                    />
                                </td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default Table;
