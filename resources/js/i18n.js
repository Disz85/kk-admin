import { initReactI18next } from "react-i18next";
import i18n from "i18next";

import users from "./Translations/users";

const resources = {
    hu: {
        translation: {
            'users' : users,
        }
    }
};

i18n.use(initReactI18next)
    .init({
        interpolation: { escapeValue: false },
        resources,
        lng: "hu",
    });

export default i18n;
