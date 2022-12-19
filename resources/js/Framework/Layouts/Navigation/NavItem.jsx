import React from 'react';
import PropTypes from 'prop-types';

import { NavLink } from 'react-router-dom';

import style from '../../../../scss/layouts/navigation.module.scss';

const NavItem = ({ title, path }) => {
    return (
        <li key={title} className={style.item}>
            <NavLink className={style.link} to={path}>
                <span className={style.text}>{title}</span>
            </NavLink>
        </li>
    );
};

NavItem.propTypes = {
    /**
     * Type of title
     */
    title: PropTypes.string.isRequired,
    /**
     * Type of path
     */
    path: PropTypes.string.isRequired,
};

export default NavItem;
