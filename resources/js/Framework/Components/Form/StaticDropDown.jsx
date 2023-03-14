import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';

const StaticDropDown = ({
    entity,
    path,
    value,
    label,
    onChange,
    service,
    readonly,
    ...props
}) => {
    const { name, resource } = props;
    const [options, setOptions] = useState([]);
    const { t } = useTranslation();

    useEffect(() => {
        service.get(path).then((result) => {
            const resultOptions = [...result];

            setOptions(
                resultOptions.map((item) => ({
                    id: item,
                    label: t(`${resource}.${item}`),
                })),
            );
        });
    }, []);

    const change = (e) => {
        onChange({
            [name]: e.target.value !== '' ? e.target.value : null,
        });
    };

    return (
        <div className={style.formGroup}>
            <Field {...props}>
                {(attributes) => {
                    const { hasError, ...attr } = attributes;

                    return (
                        options && (
                            <select
                                className={style.select}
                                value={
                                    entity[name]
                                        ? (Object.hasOwnProperty.call(
                                              entity[name],
                                              value,
                                          ) &&
                                              entity[name][value]) ||
                                          entity[name]
                                        : ''
                                }
                                onChange={change}
                                disabled={readonly}
                                {...attr}
                            >
                                <option value="">&nbsp;</option>
                                {options.map((item) => {
                                    return (
                                        <option value={item.id} key={item.id}>
                                            {item.label}
                                        </option>
                                    );
                                })}
                            </select>
                        )
                    );
                }}
            </Field>
        </div>
    );
};

export default StaticDropDown;

StaticDropDown.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of path
     */
    path: PropTypes.string.isRequired,
    /**
     * Type of options
     */
    options: PropTypes.array.isRequired,
    /**
     * Type of value
     */
    value: PropTypes.string.isRequired,
    /**
     * Type of label
     */
    label: PropTypes.string.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of readonly
     */
    readonly: PropTypes.bool,
};

StaticDropDown.defaultProps = {
    readonly: false,
};
