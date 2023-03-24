import React from 'react';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// ICONS
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheck } from '@fortawesome/free-solid-svg-icons';

// STYLE
import style from '../../../../scss/components/buttons/button.module.scss';

const Approve = () => {
    const { t } = useTranslation();

    return (
        <button className={style.approve} type="submit">
            <span className={style.approveText}>
                {t('application.approve')}
            </span>
            <FontAwesomeIcon className={style.approveIcon} icon={faCheck} />
        </button>
    );
};

export default Approve;
