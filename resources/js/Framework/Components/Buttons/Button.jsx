import React from 'react';
import PropTypes from 'prop-types';

import { useTranslation } from 'react-i18next';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

// STYLES
import style from '../../../../scss/components/buttons/button.module.scss';

const Button = ({
    name = undefined,
    click,
    unlabeled = false,
    icon = false,
    classNames,
    ...props
}) => {
    const { t } = useTranslation();

    return (
        <button
            className={`${style.button} ${classNames}`}
            type="button"
            onClick={click}
            {...props}
        >
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
     * Type of classNames
     */
    classNames: PropTypes.string,
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
    unlabeled: false,
    classNames: '',
};
