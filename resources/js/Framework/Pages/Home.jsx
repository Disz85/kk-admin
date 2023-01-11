import React, { useEffect, useContext } from 'react';
// TRANSLATION
import { useTranslation } from 'react-i18next';
// CONTEXTS
import ApplicationContext from '../Context/ApplicationContext';

const Home = () => {
    // CONTEXTS
    const setPageInfo = useContext(ApplicationContext);
    const { t } = useTranslation();

    // SIDE EFFECTS
    useEffect(() => {
        setPageInfo({ title: t(`application.home`), icon: 'icon' });
    }, []);

    return <h2>Home</h2>;
};

export default Home;
