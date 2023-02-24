import { initReactI18next } from 'react-i18next';
import i18n from 'i18next';

import application from './Translations/application';
import users from './Translations/users';
import tags from './Translations/tags';
import categories from './Translations/categories';
import authors from './Translations/authors';
import articles from './Translations/articles';
import brands from './Translations/brands';
import products from './Translations/products';
import editor from './Translations/editor';

const resources = {
    hu: {
        translation: {
            application,
            logout: application,
            users,
            tags,
            categories,
            authors,
            articles,
            brands,
            products,
            editor,
        },
    },
};

i18n.use(initReactI18next).init({
    interpolation: { escapeValue: false },
    resources,
    lng: 'hu',
});

export default i18n;
