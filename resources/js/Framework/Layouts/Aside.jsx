import React from 'react';
import PropTypes from 'prop-types';

import { motion } from 'framer-motion';

// CONFIG
import navigationAnimate from '../../config/animation/navigationAnimate';

import style from '../../../scss/layouts/aside.module.scss';

const Aside = ({ action, children }) => {
    return (
        <motion.aside
            className={style.wrapper}
            initial="open"
            animate={action ? 'closed' : 'open'}
            variants={navigationAnimate}
        >
            {children}
        </motion.aside>
    );
};

export default Aside;

Aside.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,

    action: PropTypes.bool.isRequired,
};
