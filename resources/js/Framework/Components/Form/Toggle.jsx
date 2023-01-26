import React from 'react';
import PropTypes from 'prop-types';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';

const Toggle = ({ onChange, entity, ...props }) => {
    // SIDE EFFECTS
    const change = () => onChange({ [props.name]: !entity[props.name] });

    // RENDER
    return (
        <div className={style.formGroup}>
            <Field {...props}>
                {(hasError, attributes) => (
                    <input
                        className={`${style.formToggle} ${
                            hasError ? style.isInvalid : ''
                        } ${entity[props.name] ? style.checked : ''}`}
                        type="checkbox"
                        role="switch"
                        onChange={change}
                        checked={entity[props.name]}
                        autoComplete="off"
                        {...attributes}
                    />
                )}
            </Field>
        </div>
    );
};

export default Toggle;

Toggle.propTypes = {
    /**
     * entity of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
};
