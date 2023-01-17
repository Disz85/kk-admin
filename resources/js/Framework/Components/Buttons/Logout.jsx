import React from 'react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowRightFromBracket } from '@fortawesome/free-solid-svg-icons';

import { Link } from 'react-router-dom';

import { useTranslation } from 'react-i18next';

import style from '../../../../scss/components/buttons/logout.module.scss';

const Logout = () => {
    const { t } = useTranslation();
    return (
        <Link className={style.anchor} to="/logout">
            <FontAwesomeIcon
                className={style.icon}
                icon={faArrowRightFromBracket}
                size="2x"
            />
            <span className={style.text}>{t('application.logout')}</span>
        </Link>
    );
};

export default Logout;
