import React from 'react';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// ICONS
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSave } from '@fortawesome/free-solid-svg-icons';

// STYLE
import style from '../../../../scss/components/buttons/button.module.scss';

const Submit = () => {
    const { t } = useTranslation();

    return (
        <button className={style.submit} type="submit">
            <span className={style.submitText}>{t('application.save')}</span>
            <FontAwesomeIcon className={style.submitIcon} icon={faSave} />
        </button>
    );
};

export default Submit;
