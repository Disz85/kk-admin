import React from 'react';
import PropTypes from 'prop-types';

import NavItem from './NavItem';

import style from '../../../../scss/layouts/navigation.module.scss';

const Navigation = ({ items }) => {
    return (
        <nav className={style.menu}>
            <ul className={style.list}>
                {items.map(({ name, path }) => (
                    <NavItem key={name} title={name} path={path} />
                ))}
            </ul>
        </nav>
    );
};

Navigation.propTypes = {
    /**
     * Type of resources
     */
    items: PropTypes.shape({
        map: PropTypes.shape.isRequired,
        name: PropTypes.string,
        path: PropTypes.string,
    }).isRequired,
};

export default Navigation;
