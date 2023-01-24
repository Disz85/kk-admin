import React from 'react';
import PropTypes from 'prop-types';
// ANIMATION
// TRANSLATION
import { useTranslation } from 'react-i18next';
// ICONS
import { faHome } from '@fortawesome/free-solid-svg-icons';
// CONFIG
import navigationIcons from '../../../config/navigationIcons';
// COMPONENTS
import NavItem from './NavItem';

import style from '../../../../scss/layouts/navigation.module.scss';

const Navigation = ({ items, action }) => {
    // Contexts
    const { t } = useTranslation();
    const ANR = ' ';

    return (
        <nav className={style.menu}>
            <ul
                className={`${style.list} ${
                    action ? ANR + style.listClosed : ''
                }`}
            >
                <NavItem
                    key="home"
                    title={t('application.home')}
                    path="/"
                    action={action}
                    icon={faHome}
                />
                {items.map(({ name, path }) => (
                    <NavItem
                        key={name}
                        title={t(`${name}.${name}`)}
                        path={path}
                        action={action}
                        icon={navigationIcons[name]}
                    />
                ))}
            </ul>
        </nav>
    );
};

Navigation.propTypes = {
    /**
     * Type of resources
     */
    items: PropTypes.arrayOf(
        PropTypes.shape({
            // map: PropTypes.shape.isRequired,
            name: PropTypes.string,
            path: PropTypes.string,
        }),
    ).isRequired,
    /**
     * Type of action
     */
    action: PropTypes.bool.isRequired,
};

export default Navigation;
