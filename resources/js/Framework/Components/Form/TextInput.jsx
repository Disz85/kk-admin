import React from 'react';
import PropTypes from 'prop-types';

import Field from './Field';

import style from '../../../../scss/components/form.module.scss';

const TextInput = ({ onChange, entity, regex = null, ...props }) => {
    // SIDE EFFECTS
    const change = (e) =>
        onChange({
            [props.name]: regex
                ? e.target.value.replace(regex, '')
                : e.target.value,
        });
    // RENDER
    return (
        <div className={style.formGroup}>
            <Field {...props}>
                {(hasError, attributes) => (
                    <input
                        className={`${style.formTextInput} ${hasError ? style.isInvalid : ''}`}
                        type="text"
                        onChange={change}
                        value={entity[props.name] || ''}
                        autoComplete="off"
                        {...attributes}
                    />
                )}
            </Field>
        </div>
    );
};

export default TextInput;

TextInput.propTypes = {
    /**
     * entity of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of regex
     */
    regex: PropTypes.instanceOf(RegExp),
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
};

TextInput.defaultProps = {
    regex: null,
};
