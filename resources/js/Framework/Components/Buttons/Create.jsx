import React from 'react';
import PropTypes from 'prop-types';
// ROUTES
import { Link } from 'react-router-dom';
// TRANSLATION
import { useTranslation } from 'react-i18next';

// ICONS
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';

// STYLE
import style from '../../../../scss/components/buttons/button.module.scss';

const Create = ({ resource }) => {
    const { t } = useTranslation();
    let entity = resource;

    if (entity.includes('categories')) {
        entity = 'categories';
    }

    return (
        <Link className={style.create} to={`/${entity}/new`}>
            <span className={style.createText}>
                {t('application.new', { resource: t(`${entity}.resource`) })}
            </span>
            <FontAwesomeIcon className={style.createIcon} icon={faPlus} />
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
