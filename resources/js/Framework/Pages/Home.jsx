import React, { useEffect, useContext } from 'react';
import PropTypes from 'prop-types';
// FONT
import { faHome } from '@fortawesome/free-solid-svg-icons';
// TRANSLATION
import { useTranslation } from 'react-i18next';
// CONFIG
import navigationIcons from '../../config/navigationIcons';
// CONTEXTS
import ApplicationContext from '../Context/ApplicationContext';
// COMPONENTS
import Card from '../Components/Card';
// STYLES
import style from '../../../scss/pages/home.module.scss';

const Home = ({ resources }) => {
    // CONTEXTS
    const setPageInfo = useContext(ApplicationContext);
    const { t } = useTranslation();

    // SIDE EFFECTS
    useEffect(() => {
        setPageInfo({ title: t(`application.home`), icon: faHome });
    }, []);

    return (
        <div className={style.wrapper}>
            {resources
                .filter(
                    ({ name, groupParent }) =>
                        name !== 'logout' && !groupParent,
                )
                .map(({ name, path }) => (
                    <Card
                        key={name}
                        path={path}
                        icon={navigationIcons[name]}
                        title={name}
                    />
                ))}
        </div>
    );
};

export default Home;

Home.propTypes = {
    /**
     * Type of resources
     */
    resources: PropTypes.array.isRequired,
};
