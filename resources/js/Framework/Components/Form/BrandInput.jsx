import React from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import AutoComplete from './AutoComplete';

const BrandInput = ({ entity, onChange, resource, service, errors }) => {
    const { t } = useTranslation();

    return (
        <AutoComplete
            entity={entity}
            onChange={onChange}
            resource={resource}
            service={service}
            name="brand"
            searchBy="title"
            reference="brands"
            placeholder={t('application.brands')}
            isMultiple={false}
            errors={errors}
        />
    );
};

export default BrandInput;

BrandInput.propTypes = {
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
