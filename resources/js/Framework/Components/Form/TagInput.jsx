import React from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import AutoComplete from './AutoComplete';

const TagInput = ({ entity, onChange, resource, service, errors }) => {
    const { t } = useTranslation();

    return (
        <AutoComplete
            entity={entity}
            onChange={onChange}
            resource={resource}
            service={service}
            name="tags"
            searchBy="name"
            reference="tags"
            placeholder={t('application.tags')}
            isMultiple
            errors={errors}
        />
    );
};

export default TagInput;

TagInput.propTypes = {
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
