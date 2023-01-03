import { useTranslation } from "react-i18next";
import React from "react";


export const Button = ({ name, click, icon = null, transparent = false, list = false, dynamic = false, className = '', ...props }) => {
    const { t } = useTranslation();

    const listStyle = (list ? "a-link -default" : "m-button -ternaryColor");
    const listIconStyle = (list ? "-colorPrimary" : "-bgColorSecondary -colorQuinary -circle -sm p-3 ml-2");

    const buttonStyle = !transparent && !list ? "m-button -primaryColor py-2 px-4 d-flex justify-content-center align-items-center" : listStyle;
    const iconStyle = `a-icon ${!transparent && !list ? "-hoverActive -colorQuinary -sm" : listIconStyle }`;

    const { unlabeled = false } = props;


    return (

        <button type={ "button" } className={ `${buttonStyle} ${className}` } onClick={ click } tabIndex={ -1 }>
            { !unlabeled &&
                <span className={ `m-button__text ${!dynamic ? '-static': ''}` }>
                    { t(`application.${name}`) || '' }
                </span>
            }
            { icon &&  <i className={ `${ iconStyle } fal fa-${ icon }` }></i> }
        </button>
    );
};
