import React from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import AutoComplete from './AutoComplete';

const CategoryInput = ({ entity, onChange, resource, service, errors }) => {
    const { t } = useTranslation();

    return (
        <AutoComplete
            entity={entity}
            onChange={onChange}
            resource={resource}
            service={service}
            name="categories"
            searchBy="name"
            reference="categories/article"
            placeholder={t('application.category-search')}
            isMultiple
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
     * Type of errors
     */
    errors: PropTypes.object.isRequired,
};
