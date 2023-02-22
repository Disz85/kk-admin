import React, { useState, useRef } from 'react';
import PropTypes from 'prop-types';
import Modal from 'react-modal';

// ICONS
import { faUpload, faCrop, faTrash } from '@fortawesome/free-solid-svg-icons';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

import MediaService from '../../../Services/MediaService';
import useUpdateEffect from '../../../Hooks/useUpdateEffect';

// COMPONENTS
import ImageCropper from './ImageCropper';
import { cropParams, sanitizeCropValues } from '../../../Helpers/imageCropper';
import Error from '../Form/Error';
import Button from '../Buttons/Button';
import Flex from '../../Layouts/Flex';
import ImagePlaceholder from './ImagePlaceholder';
import Label from '../Form/Label';

// STYLES
import style from '../../../../scss/components/image.module.scss';
import form from '../../../../scss/components/form.module.scss';

const Image = ({
    name,
    resource,
    entity,
    onChange,
    errors,
    isCropping = true,
    isDetachable = false,
}) => {
    const input = useRef(null);

    const { t } = useTranslation();

    const [media, setMedia] = useState(entity[name]);
    const [cropping, setCropping] = useState(false);
    const pickImage = () => input.current.click();

    const upload = (e) => {
        MediaService.upload(e.target.files[0], resource).then(({ data }) => {
            setMedia((old) => ({ ...old, ...data }));
        });
    };

    const detachImage = () => {
        setMedia(null);
    };

    const toggleCropping = () =>
        setCropping((currentCropping) => !currentCropping);

    const applyCropping = ({ width, height, x, y }) =>
        setMedia((old) => ({
            ...old,
            ...sanitizeCropValues({ width, height, x, y }),
        }));

    const { path, id, type, ...crop } = media || {};

    useUpdateEffect(() => {
        setCropping(false);

        let change = { [name]: null };

        if (media) {
            change = {
                [name]: {
                    id: media.id,
                    width: media.width,
                    height: media.height,
                    x: media.x,
                    y: media.y,
                },
            };
        }

        onChange(change);
    }, [media]);

    return (
        <div className={form.formGroup}>
            <Label className={form.label} to="image-wrapper">
                {t('application.image')}
            </Label>
            <div
                id="image-wrapper"
                className={`${style.imageWrapper} ${
                    errors[`${name}.id`] ? form.isInvalid : ''
                }`}
            >
                <input
                    type="file"
                    name={name}
                    ref={input}
                    onChange={upload}
                    style={{ display: 'none' }}
                    accept={'image/*'}
                />
                {media ? (
                    <div className={style.image}>
                        <img
                            className={style.coverImage}
                            src={cropParams(
                                `${import.meta.env.VITE_IMAGE_URL}/${path}`,
                                crop,
                            )}
                            alt=""
                        />
                        <Modal
                            isOpen={cropping}
                            onRequestClose={toggleCropping}
                            className={style.cropModal}
                            overlayClassName={style.cropModalOverlay}
                            ariaHideApp={false}
                        >
                            <ImageCropper
                                media={media}
                                crop={crop}
                                applyCropping={applyCropping}
                            />
                        </Modal>
                        {isCropping && (
                            <Button
                                name="crop"
                                icon={faCrop}
                                click={toggleCropping}
                                type="button"
                                unlabeled
                            />
                        )}
                    </div>
                ) : (
                    <ImagePlaceholder />
                )}
                <Flex classNames={style.imageBtnWrapper}>
                    <Button
                        classNames={style.uploadBtn}
                        name="image-upload"
                        text={t('application.imageUpload')}
                        icon={faUpload}
                        click={pickImage}
                        type="button"
                    />
                    {isDetachable && (
                        <Button
                            classNames={style.resetBtn}
                            name="reset"
                            text={t('application.reset')}
                            icon={faTrash}
                            click={detachImage}
                            type="button"
                        />
                    )}
                </Flex>
                {Object.hasOwnProperty.call(errors, `${name}.id`) && (
                    <Error message={errors[`${name}.id`][0]} />
                )}
            </div>
        </div>
    );
};

Image.propTypes = {
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of entity
     */
    resource: PropTypes.object.isRequired,
    /**
     * Entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of errors
     */
    errors: PropTypes.array,
    /**
     * Type of isCropping
     */
    isCropping: PropTypes.bool,
    /**
     * Type of isDetachable
     */
    isDetachable: PropTypes.bool,
};

Image.defaultProps = {
    errors: [],
    isCropping: false,
    isDetachable: false,
};

export default Image;
