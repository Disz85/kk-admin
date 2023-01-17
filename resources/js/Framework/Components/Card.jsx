import React from 'react';
import PropTypes from 'prop-types';

import { NavLink } from 'react-router-dom';

import { motion } from 'framer-motion';

import { useTranslation } from 'react-i18next';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import cardAnimate from '../../config/animation/cardAnimate';

import style from '../../../scss/components/card.module.scss';

const Card = ({ title, icon, path }) => {
    const { t } = useTranslation();

    return (
        <NavLink to={path}>
            <motion.div
                whileHover={cardAnimate.hover}
                className={style.wrapper}
            >
                <FontAwesomeIcon className={style.icon} icon={icon} />
                <h2 className={style.title}>{t(`${title}.${title}`)}</h2>
            </motion.div>
        </NavLink>
    );
};

export default Card;

Card.propTypes = {
    /**
     * Type of icon
     */
    icon: PropTypes.object.isRequired,
    /**
     * Type of title
     */
    title: PropTypes.string.isRequired,
    /**
     * Type of path
     */
    path: PropTypes.string.isRequired,
};
