import React from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// ICONS
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTimes } from '@fortawesome/free-solid-svg-icons';

// STYLES
import style from '../../../../scss/components/buttons/button.module.scss';

const Reject = ({ entity, remove }) => {
    const { t } = useTranslation();

    return (
        <button
            className={style.reject}
            type="button"
            onClick={() => remove(entity)}
        >
            <span className={style.rejectText}>{t('application.reject')}</span>
            <FontAwesomeIcon className={style.rejectIcon} icon={faTimes} />
        </button>
    );
};

export default Reject;

Reject.propTypes = {
    /**
     * entity of remove
     */
    remove: PropTypes.func.isRequired,
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
};
