import React from 'react';

import Logout from '../Components/Buttons/Logout';
import Logo from '../Components/Svg/Logo';
import Hamburger from '../Components/Buttons/Hamburger';

import style from '../../../scss/layouts/header.module.scss';

const Header = ({ ...props }) => {
    return (
        <header className={style.header}>
            <div className={style.wrapper}>
                <Hamburger {...props} />
                <Logo />
                <Logout />
            </div>
        </header>
    );
};

export default Header;
