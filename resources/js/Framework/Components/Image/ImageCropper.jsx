import React, { useRef, useState } from 'react';
import PropTypes from 'prop-types';
import { Cropper } from 'react-cropper';
import 'cropperjs/dist/cropper.css';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// ICONS
import { faScissors, faBan } from '@fortawesome/free-solid-svg-icons';

// COMPONENTS
import Flex from '../../Layouts/Flex';
import Button from '../Buttons/Button';

const ImageCropper = ({ media, crop, applyCropping }) => {
    const { t } = useTranslation();
    const cropperRef = useRef(null);

    const [cropData, setCropData] = useState(crop);

    const onCrop = ({ detail }) => {
        const { width, height, x, y } = detail;
        setCropData({ width, height, x, y });
    };

    const apply = () => applyCropping(cropData);

    const close = () => applyCropping(crop);

    return (
        <>
            <Cropper
                ref={cropperRef}
                src={`${import.meta.env.VITE_IMAGE_URL}/${media.path}`}
                data={cropData}
                guides
                crop={onCrop}
            />

            <Flex>
                <Button
                    name="cut"
                    text={t('application.cut')}
                    icon={faScissors}
                    click={apply}
                />
                <Button
                    name="cancel"
                    text={t('application.cancel')}
                    icon={faBan}
                    click={close}
                />
            </Flex>
        </>
    );
};

ImageCropper.propTypes = {
    /**
     * Type of media
     */
    media: PropTypes.string.isRequired,
    /**
     * Type of crop
     */
    crop: PropTypes.object.isRequired,
    /**
     * Type of applyCropping
     */
    applyCropping: PropTypes.func.isRequired,
};

export default ImageCropper;
