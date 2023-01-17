import React from 'react';

import PropTypes from 'prop-types';

import style from '../../../../scss/components/buttons/hamburger.module.scss';

const Hamburger = ({ toggle, action }) => {
    const line = {
        first: 'M 30,65 H 70',
        second: 'M 70,50 H 30 C 30,50 18.644068,50.320751 18.644068,36.016949 C 18.644068,21.712696 24.988973,6.5812347 38.79661,11.016949 C 52.604247,15.452663 46.423729,62.711864 46.423729,62.711864 L 50.423729,49.152542 L 50.423729,16.101695',
        third: 'M 30,35 H 70 C 70,35 80.084746,36.737688 80.084746,25.423729 C 80.084746,19.599612 75.882239,9.3123528 64.711864,13.559322 C 53.541489,17.806291 54.423729,62.711864 54.423729,62.711864 L 50.423729,49.152542 V 16.101695',
        closeFirst: 'M 34,32 L 66,68',
        closeSecond: 'M 66,32 L 34,68',
    };

    return (
        <div
            className={`${style.button} ${!action ? style.buttonActive : ''}`}
            onClick={toggle}
            onKeyDown={toggle}
            role="button"
            tabIndex="0"
        >
            <svg className={style.svg} version="1.1" viewBox="0 0 100 100">
                <path className={style.lineFirst} d={line.first} />
                <path className={style.lineSecond} d={line.second} />
                <path className={style.lineThird} d={line.third} />
            </svg>
            <svg className={style.svgClose} version="1.1" viewBox="0 0 100 100">
                <path className={style.lineClose} d={line.closeFirst} />
                <path className={style.lineClose} d={line.closeSecond} />
            </svg>
        </div>
    );
};

export default Hamburger;

Hamburger.propTypes = {
    /**
     * Type of toggle
     */
    toggle: PropTypes.func.isRequired,
    /**
     * Type of click
     */
    action: PropTypes.bool.isRequired,
};
