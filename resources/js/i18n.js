import { initReactI18next } from "react-i18next";
import i18n from "i18next";

import application from "./Translations/application";
import users from "./Translations/users";
import tags from "./Translations/tags";

const resources = {
    hu: {
        translation: {
            'application'   : application,
            'users'         : users,
            'tags'          : tags,
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
