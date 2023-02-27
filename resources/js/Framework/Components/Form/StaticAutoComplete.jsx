import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Typeahead } from 'react-bootstrap-typeahead';
import useUpdateEffect from '../../../Hooks/useUpdateEffect';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';

const StaticAutoComplete = ({
    entity,
    onChange,
    isMultiple,
    items,
    searchBy,
    placeholder,
    ...props
}) => {
    const { name } = props;

    const [selected, setSelected] = useState(
        entity[name]
            ? (Array.isArray(entity[name]) && entity[name]) || [entity[name]]
            : [],
    );

    useUpdateEffect(() => {
        if (isMultiple) {
            onChange({ [name]: selected });
        } else {
            onChange({ [name]: selected[0] || null });
        }
    }, [selected]);

    return (
        <div className={style.formGroup}>
            <Field {...props}>
                {(attributes) => {
                    const { hasError, ...attr } = attributes;

                    return (
                        <Typeahead
                            className={`${style.autocomplete} -${name}`}
                            placeholder={placeholder}
                            multiple={isMultiple}
                            labelKey={searchBy}
                            options={items}
                            selected={selected}
                            flip
                            onChange={setSelected}
                            inputProps={{
                                id: attributes.id,
                                className: `${style.textArea} ${
                                    hasError ? style.isInvalid : ''
                                }`,
                            }}
                            {...attr}
                        />
                    );
                }}
            </Field>
        </div>
    );
};

export default StaticAutoComplete;

StaticAutoComplete.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of placeholder
     */
    placeholder: PropTypes.string.isRequired,
    /**
     * Type of searchBy
     */
    searchBy: PropTypes.string,
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of reference
     */
    reference: PropTypes.string.isRequired,
    /**
     * Type of isMultiple
     */
    isMultiple: PropTypes.bool.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of items
     */
    items: PropTypes.array.isRequired,
};

StaticAutoComplete.defaultProps = {
    searchBy: 'name',
};
