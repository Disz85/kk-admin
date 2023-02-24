import React from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import AutoComplete from './AutoComplete';

const CategoryInput = ({
    entity,
    onChange,
    resource,
    service,
    name,
    type,
    isMultiple,
    errors,
}) => {
    const { t } = useTranslation();

    return (
        <AutoComplete
            entity={entity}
            onChange={onChange}
            resource={resource}
            service={service}
            name={name}
            searchBy="name"
            reference={`categories/${type}`}
            placeholder={t('application.categories')}
            isMultiple={isMultiple}
            errors={errors}
        />
    );
};

export default CategoryInput;

CategoryInput.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.object.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of type
     */
    type: PropTypes.string.isRequired,
    /**
     * Type of isMultiple
     */
    isMultiple: PropTypes.bool,
    /**
     * Type of errors
     */
    errors: PropTypes.object.isRequired,
};

CategoryInput.defaultProps = {
    isMultiple: true,
};
