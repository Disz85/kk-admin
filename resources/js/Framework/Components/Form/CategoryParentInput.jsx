import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import StaticDropDown from './StaticDropDown';
import AutoComplete from './AutoComplete';

const CategoryParentInput = ({
    entity,
    onChange,
    service,
    errors,
    ...props
}) => {
    const { resource } = props;
    const [type, setType] = useState(entity.type);
    const [parent, setParent] = useState(entity.parent_id);
    const { t } = useTranslation();

    useEffect(() => {
        onChange({
            type,
            parent,
        });
    }, [type, parent]);

    const onTypeChange = (item) => {
        setType(item.type);
    };

    const onParentChange = (item) => {
        setParent(item.parent);
    };

    return (
        <>
            <StaticDropDown
                path="categories/get-types"
                name="type"
                onChange={onTypeChange}
                service={service}
                value={type}
                entity={entity}
                resource={resource}
                errors={errors}
                readonly={!!entity.id}
            />
            <AutoComplete
                entity={entity}
                onChange={onParentChange}
                resource={resource}
                service={service}
                name="parent"
                searchBy="name"
                reference={`categories/${type}`}
                placeholder={t('application.categories')}
                isMultiple={false}
                errors={errors}
            />
        </>
    );
};

export default CategoryParentInput;

CategoryParentInput.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.object.isRequired,
    /**
     * Type of errors
     */
    errors: PropTypes.object.isRequired,
};
