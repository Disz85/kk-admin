import React from 'react';
import style from '../../../scss/components/table.module.scss';
import { useTranslation } from "react-i18next";

const Table = ({ fields, entities, data, remove }) => {
    const { t } = useTranslation();
    const { resource, service } = data;

    return (
        <div className={style.tableWrapper}>
            <table className={style.inner}>
                <thead className={style.header}>
                    <tr className={style.row}>
                        {fields.map(({ name }) => (
                            <th key={name} className={style.title}> { t(`${resource}.${name}`) } </th>
                        ))}
                    </tr>
                </thead>
                <tbody className={style.body}>
                    {entities && entities.map((entity) => (
                        <tr key={entity.id} className={style.row}>
                            {fields.map(({ component: Component, ...rest }) => (
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
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default Table;
