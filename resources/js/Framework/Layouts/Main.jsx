import React from 'react';
import PropTypes from 'prop-types';
import { motion } from 'framer-motion';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

// CONFIG
import mainAnimate from '../../config/animation/mainAnimate';

import style from '../../../scss/layouts/main.module.scss';

const Main = ({ description = {}, action, children }) => {
    const { title, icon } = description;

    const initialState = 'closed';
    const animation = action ? 'open' : initialState;

    return (
        <motion.main
            variants={mainAnimate}
            initial={initialState}
            animate={animation}
        >
            <div className={style.wrapper}>
                <div className={style.header}>
                    <h1 className={style.title}>
                        <FontAwesomeIcon icon={icon} />
                        <span>{title}</span>
                    </h1>
                </div>
                <div className={style.body}>{children}</div>
            </div>
        </motion.main>
    );
};

export default Main;

Main.propTypes = {
    /**
     * Type of description
     */
    description: PropTypes.shape({
        title: PropTypes.string,
        icon: PropTypes.object,
    }),
    /**
     * Type of action
     */
    action: PropTypes.bool.isRequired,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

Main.defaultProps = {
    description: {},
};
