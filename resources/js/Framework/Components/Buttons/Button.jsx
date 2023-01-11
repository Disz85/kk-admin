import React from 'react';
import PropTypes from 'prop-types';

import { useTranslation } from 'react-i18next';

const Button = ({ name, click, unlabeled = false }) => {
    const { t } = useTranslation();

    return (
        <button type="button" onClick={click} tabIndex={-1}>
            {!unlabeled && <span>{t(`application.${name}`) || ''}</span>}
        </button>
    );
};

export default Button;

Button.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of click
     */
    click: PropTypes.func.isRequired,
    /**
     * Type of unlabeled
     */
    unlabeled: PropTypes.bool,
};

Button.defaultProps = {
    unlabeled: false,
};
