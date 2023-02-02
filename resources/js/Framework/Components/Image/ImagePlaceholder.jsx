import React from 'react';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// STYLES
import style from '../../../../scss/components/image.module.scss';

const ImagePlaceholder = () => {
    const { t } = useTranslation();

    return (
        <div className={style.placeholder}>
            <h2 className={style.placeholderTitle}>
                {t('application.noImage')}
            </h2>
        </div>
    );
};

export default ImagePlaceholder;
