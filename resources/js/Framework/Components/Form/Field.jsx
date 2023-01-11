import { useTranslation } from 'react-i18next';
import React, { useState } from 'react';
import PropTypes from 'prop-types';

import { generate } from 'shortid';

import Label from './Label';
import Error from './Error';

const Field = ({
    name,
    resource,
    children,
    errors,
    path = null,
    disabled = false,
    unlabeled = false,
    recursionLevel = null,
}) => {
    const [id] = useState(generate());
    const { t } = useTranslation();

    // Fully Qualified Path
    const FQP = `${path ? `${path}.${name}` : name}`;
    const LOCALE = `${FQP}${recursionLevel ? `-${recursionLevel}` : ''}`;

    const placeholder = t(
        `${resource}.${LOCALE}`.replace(new RegExp('[.][0-9]+', 'g'), ''),
    );

    const notIn = (errors) =>
        Object.entries(errors)
            .filter(([key]) => new RegExp(`${FQP}.[0-9]+.id`).test(key))
            .map(([, error]) => error);

    const error = errors
        ? errors[`${FQP}.id`] || errors[FQP] || notIn(errors)[0] || ''
        : '';

    const attributes = { id, name, disabled, placeholder };

    const hasError = !!error;

    return (
        <>
            {!unlabeled && <Label to={id} text={placeholder} />}
            {children(hasError, attributes)}
            {errors && <Error message={error} />}
        </>
    );
};

export default Field;

Field.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
    /**
     * Type of errors
     */
    errors: PropTypes.object.isRequired,
    /**
     * Type of path
     */
    path: PropTypes.object,
    /**
     * Type of disabled
     */
    disabled: PropTypes.bool,
    /**
     * Type of unlabeled
     */
    unlabeled: PropTypes.bool,
    /**
     * Type of recursionLevel
     */
    recursionLevel: PropTypes.array,
};

Field.defaultProps = {
    path: null,
    disabled: false,
    unlabeled: false,
    recursionLevel: null,
};
