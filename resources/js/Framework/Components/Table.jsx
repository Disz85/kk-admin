import React from 'react';
import style from '../../../scss/components/table.module.scss';
import { useTranslation } from "react-i18next";

const Table = ({ fields, entities, data }) => {
    const { t } = useTranslation();

    return (
        <div className={style.tableWrapper}>
            <table className={style.inner}>
                <thead className={style.header}>
                    <tr className={style.row}>
                        {fields.map(({ name }) => (
                            <th key={name} className={style.title}> { t(`${data.resource}.${name}`) } </th>
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
