import React from 'react';
import PropTypes from 'prop-types';

import { motion } from 'framer-motion';

import { NavLink } from 'react-router-dom';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import navigationAnimate from '../../../config/animation/navigationAnimate';

import style from '../../../../scss/layouts/navigation.module.scss';

const NavItem = ({ title, path, action, icon = false, className }) => {
    const initialState = 'open';
    const animation = action ? 'closed' : initialState;

    return (
        <motion.li
            whileHover="hover"
            variants={navigationAnimate.listItem}
            key={title}
            className={`${style.item} ${className}`}
        >
            <NavLink
                className={({ isActive }) =>
                    isActive ? style.linkActive : style.link
                }
                to={path}
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
                    {title}
                </motion.span>
            </NavLink>
        </motion.li>
    );
};

export default NavItem;

NavItem.propTypes = {
    /**
     * Type of title
     */
    title: PropTypes.string.isRequired,
    /**
     * Type of path
     */
    path: PropTypes.string.isRequired,
    /**
     * Type of action
     */
    action: PropTypes.bool.isRequired,
    /**
     * Type of icon
     */
    icon: PropTypes.oneOfType([PropTypes.object, PropTypes.bool]),
    /**
     * Type of className
     */
    className: PropTypes.string,
};

NavItem.defaultProps = {
    icon: false,
    className: '',
};
