import React from 'react';
import PropTypes from 'prop-types';

import { useTranslation } from 'react-i18next';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

const Button = ({ name = undefined, click, unlabeled = false, icon = false, ...props }) => {
    const { t } = useTranslation();

    return (
        <button type="button" onClick={click} tabIndex={-1} {...props}>
            {icon && <FontAwesomeIcon icon={icon} />}
            {!unlabeled && <span>{t(`application.${name}`) || ''}</span>}
        </button>
    );
};

export default Button;

Button.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string,
    /**
     * Type of click
     */
    click: PropTypes.func.isRequired,
    /**
     * Type of icon
     */
    icon: PropTypes.oneOfType([PropTypes.func, PropTypes.bool]),
    /**
     * Type of unlabeled
     */
    unlabeled: PropTypes.bool,
};

Button.defaultProps = {
    name: undefined,
    icon: false,
};
