import React from 'react';
import PropTypes from 'prop-types';
import _ from 'lodash';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';

const TextArea = ({ entity, onChange, rows = 10, ...props }) => {
    const change = (e) => onChange({ [props.name]: e.target.value });

    return (
        <Field {...props}>
            {(attributes) => {
                const { hasError, ...attrs } = attributes;

                return (
                    <textarea
                        value={_.get(entity, props.name) || ''}
                        onChange={change}
                        rows={rows}
                        className={`${style.textArea} -${props.name} ${
                            hasError ? ` ${style.isInvalid}` : ''
                        }`}
                        {...attrs}
                    />
                );
            }}
        </Field>
    );
};

export default TextArea;

TextArea.propTypes = {
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of regex
     */
    rows: PropTypes.number,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
};

TextArea.defaultProps = {
    rows: 10,
};
