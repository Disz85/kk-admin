import React, { useState } from 'react';
import PropTypes from 'prop-types';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// FONTS
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

// ICONS
import { faAngleUp, faAngleDown } from '@fortawesome/free-solid-svg-icons';

// ANIMATIONS
import { motion } from 'framer-motion';
import navigationAnimate from '../../../config/animation/navigationAnimate';

// ICONS
import navigationIcons from '../../../config/navigationIcons';

// STYLES
import style from '../../../../scss/layouts/navigation.module.scss';

// COMPONENTS
import NavItem from './NavItem';

const NavigationGroup = ({ name, action, items }) => {
    const [categoriesOpen, setCategoriesOpen] = useState(false);
    const initialState = 'open';
    const animation = action ? 'closed' : initialState;
    const icon = navigationIcons[name];

    const toggleGroupOpen = () => {
        setCategoriesOpen(!categoriesOpen);
    };

    const { t } = useTranslation();
    return (
        <motion.li
            whileHover="hover"
            variants={navigationAnimate.listItem}
            key={name}
            className={`${style.item} ${categoriesOpen ? style.groupOpen : ''}`}
        >
            <motion.button
                type="button"
                className={style.linkParent}
                onClick={toggleGroupOpen}
            >
                {icon && (
                    <motion.span
                        className={action ? style.iconClosed : style.iconOpen}
                        initial={initialState}
                        animate={animation}
                        variants={navigationAnimate.icon}
                    >
                        <FontAwesomeIcon icon={icon} />
                    </motion.span>
                )}
                <motion.span
                    className={style.text}
                    initial={initialState}
                    animate={animation}
                    variants={navigationAnimate.text}
                >
                    {t(`${name}.${name}`)}
                </motion.span>
                {!action && (
                    <span className={style.angle}>
                        <FontAwesomeIcon
                            icon={categoriesOpen ? faAngleUp : faAngleDown}
                        />
                    </span>
                )}
            </motion.button>
            <ul>
                {items.map(({ name, group, path }) => {
                    return (
                        <NavItem
                            key={name}
                            title={t(`${group ?? name}.${name}`)}
                            path={path}
                            action={action}
                            icon={navigationIcons[name]}
                            className={!categoriesOpen ? style.groupClosed : ''}
                        />
                    );
                })}
            </ul>
        </motion.li>
    );
};

export default NavigationGroup;

NavigationGroup.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of disabled
     */
    action: PropTypes.bool,
    /**
     * Type of items
     */
    items: PropTypes.array.isRequired,
};

NavigationGroup.defaultProps = {
    action: false,
};
