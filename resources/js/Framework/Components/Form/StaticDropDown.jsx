import React from 'react';
import PropTypes from 'prop-types';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';

const StaticDropDown = ({
    entity,
    options,
    value,
    label,
    onChange,
    ...props
}) => {
    const { name } = props;

    const change = (e) => {
        onChange({
            [name]: e.target.value !== '' ? e.target.value : null,
        });
    };

    return (
        <Field styleType="text" labelStyle={`-${name}`} {...props}>
            {(attributes) => {
                const { hasError, ...attr } = attributes;

                return (
                    <select
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
                        {...attr}
                    >
                        <option value="">&nbsp;</option>
                        {options.map((item, index) => {
                            return (
                                <option
                                    value={item[value]}
                                    key={item.id || item.value || index}
                                >
                                    {item[label]}
                                </option>
                            );
                        })}
                    </select>
                );
            }}
        </Field>
    );
};

export default StaticDropDown;

StaticDropDown.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of options
     */
    options: PropTypes.object.isRequired,
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
     * Type of name
     */
    name: PropTypes.string.isRequired,
};
