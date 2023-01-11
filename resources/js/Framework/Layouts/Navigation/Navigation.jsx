import React from 'react';
import PropTypes from 'prop-types';

import { useTranslation } from 'react-i18next';

import NavItem from './NavItem';

import style from '../../../../scss/layouts/navigation.module.scss';

const Navigation = ({ items }) => {
    // Contexts
    const { t } = useTranslation();

    return (
        <nav className={style.menu}>
            <ul className={style.list}>
                <NavItem key="home" title={t('application.home')} path="/" />
                {items.map(({ name, path }) => (
                    <NavItem
                        key={name}
                        title={t(`${name}.${name}`)}
                        path={path}
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
};

export default Navigation;
