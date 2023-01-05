import React from 'react';
import PropTypes from 'prop-types';
import style from '../../../scss/layouts/aside.module.scss';

const Aside = ({ children }) => {
    return <aside className={style.wrapper}>{children}</aside>;
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
};
