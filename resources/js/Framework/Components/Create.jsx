import { Link } from 'react-router-dom';
import React from 'react';
import { useTranslation } from "react-i18next";

const Create = ({ resource }) => {
    const {t} = useTranslation();
    return(
        <Link to={`/${resource}/new`} className={'m-button -add'}>
                <span className="m-button__text">
                    {t('application.new', {resource: t(`${resource}.resource`)})}
                </span>
            <i className="far fa-plus d-inline-block"></i>
        </Link>
    );
};

export default Create;
