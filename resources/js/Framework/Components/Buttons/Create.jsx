import React from 'react';
import PropTypes from 'prop-types';
// ROUTES
import { Link } from 'react-router-dom';
// TRANSLATION
import { useTranslation } from 'react-i18next';

//STYLE
import style from '../../../../scss/components/buttons/button.module.scss';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import { faPlus } from '@fortawesome/free-solid-svg-icons';

const Create = ({ resource }) => {
    const { t } = useTranslation();

    return (
        <Link className={style.create} to={`/${resource}/new`}>
            <span className={style.createText}>
                {t('application.new', { resource: t(`${resource}.resource`) })}
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
