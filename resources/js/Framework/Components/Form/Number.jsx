import React from 'react';
import PropTypes from 'prop-types';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';

const Number = ({ entity, onChange, ...props }) => {
    const change = (e) => onChange({ [props.name]: e.target.value });

    return (
        <div className={style.formGroup}>
            <Field {...props}>
                {(hasError, attributes) => (
                    <input
                        className={`${style.formNumber} ${
                            hasError ? style.isInvalid : ''
                        }`}
                        type="number"
                        onChange={change}
                        value={entity[props.name] || ''}
                        min={props.min}
                        max={props.max}
                        {...attributes}
                    />
                )}
            </Field>
        </div>
    );
};

export default Number;

Number.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of min
     */
    min: PropTypes.number.isRequired,
    /**
     * Type of max
     */
    max: PropTypes.number.isRequired,
};
