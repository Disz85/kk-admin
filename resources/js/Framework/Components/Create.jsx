import React from 'react';
import PropTypes from 'prop-types';
// ROUTES
import { Link } from 'react-router-dom';
// TRANSLATION
import { useTranslation } from 'react-i18next';

const Create = ({ resource }) => {
    const { t } = useTranslation();

    return (
        <Link to={`/${resource}/new`}>
            <span>
                {t('application.new', { resource: t(`${resource}.resource`) })}
            </span>
        </Link>
    );
};

export default Create;

Create.propTypes = {
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
};
