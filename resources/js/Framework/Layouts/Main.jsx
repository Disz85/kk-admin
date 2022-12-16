import React from 'react';
import PropTypes from 'prop-types';

import style from '../../../scss/layouts/main.module.scss';

const Main = ({ description = {}, children }) => {
    const { title, icon } = description;

    return (
        <main>
            <div className={style.wrapper}>
                <div className={style.header}>
                    <h1 className={`${style.title} ${style[icon]}`}>{title}</h1>
                </div>
                <div className={style.body}>{children}</div>
            </div>
        </main>
    );
};

export default Main;

Main.propTypes = {
    /**
     * Type of description
     */
    description: PropTypes.shape({
        title: PropTypes.string,
        icon: PropTypes.string,
    }),
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
